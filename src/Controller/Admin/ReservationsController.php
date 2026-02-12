<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\View\JsonView;

/**
 * Reservations Controller
 *
 * @property \App\Model\Table\ReservationsTable $Reservations
 */
class ReservationsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $year = (int)$this->request->getQuery('year');
        $month = (int)$this->request->getQuery('month');
        $businessDayId = (int)$this->request->getQuery('business_day_id');

        // まず営業日候補（ドロップダウン用）を作る
        $businessDaysTable = $this->fetchTable('BusinessDays');

        $bdQuery = $businessDaysTable->find()
            ->select(['id', 'business_date'])
            ->orderBy(['business_date' => 'DESC']);

        // 年月が指定されていれば、その月の営業日だけ候補にする
        if ($year > 0 && $month >= 1 && $month <= 12) {
            $start = sprintf('%04d-%02d-01', $year, $month);
            $end = (new \DateTimeImmutable($start))->modify('first day of next month')->format('Y-m-d');

            $bdQuery->where([
                'BusinessDays.business_date >=' => $start,
                'BusinessDays.business_date <' => $end,
            ]);
        } elseif ($year > 0) {
            $start = sprintf('%04d-01-01', $year);
            $end = sprintf('%04d-01-01', $year + 1);

            $bdQuery->where([
                'BusinessDays.business_date >=' => $start,
                'BusinessDays.business_date <'  => $end,
            ]);
        }

        $businessDays = $bdQuery->all();

        // select用 options（例：id => '2026/02/28'）
        $businessDayOptions = [];
        foreach ($businessDays as $bd) {
            $businessDayOptions[$bd->id] = $bd->business_date->i18nFormat('yyyy/MM/dd');
        }

        // 次に予約一覧クエリ
        $query = $this->Reservations->find()
            ->contain(['BusinessDays'])
            ->orderBy(['Reservations.id' => 'DESC']);

        // 営業日で絞る（年月で候補を絞って、さらに任意で1日選べる）
        if ($businessDayId > 0) {
            $query->where(['Reservations.business_day_id' => $businessDayId]);
        } else {
            // business_day_id未指定で、年月だけ指定されている場合は
            // その年月の営業日に該当する予約に絞る
            if (!empty($businessDayOptions)) {
                $query->where(['Reservations.business_day_id IN' => array_keys($businessDayOptions)]);
            } elseif ($year > 0 || $month > 0) {
                // 年月を入れたけど営業日が0件なら、予約も0件にする
                $query->where(['1 = 0']);
            }
        }

        // 30件ページネーション
        $this->paginate = [
            'limit' => 30,
        ];
        $reservations = $this->paginate($query);

        $this->set(compact(
            'reservations',
            'year',
            'month',
            'businessDayId',
            'businessDayOptions'
        ));
    }

    /**
     * View method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reservation = $this->Reservations->get($id, [
            'contain' => [
                'BusinessDays',
                'ReservationItems',
            ],
        ]);

        $this->set(compact('reservation'));
    }

    public function updateItems($id = null)
    {
        $this->request->allowMethod(['post']);

        $reservation = $this->Reservations->get($id, [
            'contain' => ['ReservationItems'],
        ]);

        $qtyMap = (array)$this->request->getData('qty');
        $deleteMap = (array)$this->request->getData('delete');

        $reservationItems = $this->fetchTable('ReservationItems');

        $conn = $this->Reservations->getConnection();
        $conn->begin();

        try {
            // この予約の明細をDBから取り直す
            $items = $reservationItems->find()
                ->where(['reservation_id' => $reservation->id])
                ->all();

            // ✅ ① 更新前/更新後の数量を product_id ごとに集計
            $beforeMap = []; // product_id => 更新前合計（この予約分）
            $afterMap  = []; // product_id => 更新後合計（この予約分）

            foreach ($items as $item) {
                $itemId = (int)$item->id;
                $pid = (int)$item->product_id;

                // 更新前（この予約が元々持ってる数量）
                $beforeMap[$pid] = ($beforeMap[$pid] ?? 0) + (int)$item->quantity;

                // 削除チェック or 0以下なら更新後は増えない（=0扱い）
                if (!empty($deleteMap[$itemId])) {
                    continue;
                }

                $newQty = isset($qtyMap[$itemId]) ? (int)$qtyMap[$itemId] : (int)$item->quantity;
                if ($newQty <= 0) {
                    continue;
                }

                // 更新後（この予約が最終的に持つ数量）
                $afterMap[$pid] = ($afterMap[$pid] ?? 0) + $newQty;
            }

            // ✅ ② 在庫チェック（この予約が reserved のときのみチェック）
            // canceled の予約を編集しても在庫は消費していない想定なので、まずは reserved の時に守る
            if ($reservation->status === 'reserved' && !empty($afterMap)) {

                // 全ユーザーの予約済み合計（status=reservedのみ）
                $reservedMap = $reservationItems->sumReservedQuantityByProductIds(array_keys($afterMap));

                // max_quantity を見るため products を取得
                $products = $this->fetchTable('Products')->find()
                    ->where(['Products.id IN' => array_keys($afterMap)])
                    ->contain(['ProductMasters'])
                    ->all()
                    ->indexBy('id')
                    ->toArray();

                foreach ($afterMap as $pid => $afterQty) {
                    $p = $products[$pid] ?? null;
                    if (!$p) {
                        throw new \RuntimeException('不正な商品が含まれています。');
                    }

                    // max_quantity が null なら無制限
                    if ($p->max_quantity === null) {
                        continue;
                    }

                    $reservedAll = $reservedMap[$pid] ?? 0;

                    // ★重要：全体合計には「この予約の更新前数量」も含まれるので差し引く
                    $others = $reservedAll - ($beforeMap[$pid] ?? 0);
                    if ($others < 0) $others = 0;

                    // 他人分 + この予約の更新後 が上限超えたらNG
                    if ($others + $afterQty > (int)$p->max_quantity) {
                        $remain = max(0, (int)$p->max_quantity - $others);
                        throw new \RuntimeException(
                            '在庫不足：' . $p->product_master->name . '（残り ' . $remain . '）'
                        );
                    }
                }
            }

            // ✅ ③ 在庫OKなら、ここから実際に更新/削除を実行
            $newTotal = 0;
            $remainCount = 0;

            foreach ($items as $item) {
                $itemId = (int)$item->id;

                if (!empty($deleteMap[$itemId])) {
                    if (!$reservationItems->delete($item)) {
                        throw new \RuntimeException('明細の削除に失敗しました');
                    }
                    continue;
                }

                $newQty = isset($qtyMap[$itemId]) ? (int)$qtyMap[$itemId] : (int)$item->quantity;

                if ($newQty <= 0) {
                    if (!$reservationItems->delete($item)) {
                        throw new \RuntimeException('明細の削除に失敗しました');
                    }
                    continue;
                }

                $item->quantity = $newQty;
                if (!$reservationItems->save($item)) {
                    throw new \RuntimeException('明細の更新に失敗しました');
                }

                $remainCount++;
                $newTotal += ((int)$item->price_at_order) * $newQty;
            }

            if ($remainCount === 0) {
                $reservation->status = 'canceled';
                $reservation->total_price = 0;
            } else {
                $reservation->total_price = $newTotal;
            }

            if (!$this->Reservations->save($reservation)) {
                throw new \RuntimeException('予約の更新に失敗しました');
            }

            $conn->commit();

            $this->Flash->success('予約内容を更新しました');
            return $this->redirect(['action' => 'view', $reservation->id]);

        } catch (\Throwable $e) {
            $conn->rollback();

            // ✅ 失敗理由（在庫不足など）をそのまま表示すると原因がわかりやすい
            $this->Flash->error($e->getMessage());

            return $this->redirect(['action' => 'view', $reservation->id]);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reservation = $this->Reservations->newEmptyEntity();

        $businessDays = $this->Reservations->BusinessDays->find('list', [
                'keyField' => 'id',
                'valueField' => 'business_date',
            ])
            ->where(['BusinessDays.is_active' => true])
            ->orderBy(['BusinessDays.business_date' => 'DESC']);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $businessDayId = (int)($data['business_day_id'] ?? 0);

            // 数量（1以上だけ）
            $items = [];
            foreach ((array)($data['qty'] ?? []) as $productId => $q) {
                $q = (int)$q;
                if ($q > 0) {
                    $items[] = ['product_id' => (int)$productId, 'quantity' => $q];
                }
            }

            // ✅ 追加：今回の数量を product_id ごとに合算
            $requestedMap = [];
            foreach ($items as $it) {
                $pid = (int)$it['product_id'];
                $requestedMap[$pid] = ($requestedMap[$pid] ?? 0) + (int)$it['quantity'];
            }

            if ($businessDayId <= 0) {
                $this->Flash->error('営業日を選択してください。');
            } elseif (empty($items)) {
                $this->Flash->error('商品と数量を入力してください。');
            } elseif (empty($data['customer_name']) || empty($data['phone'])) {
                $this->Flash->error('氏名と電話番号は必須です。');
            } else {
                // その営業日の出品をDBから再取得（改ざん対策）
                $productsQuery = $this->fetchTable('Products')->find()
                    ->where([
                        'Products.business_day_id' => $businessDayId,
                        'Products.is_active' => true,
                    ])
                    ->contain(['ProductMasters'])
                    ->orderBy(['Products.id' => 'ASC']);

                $products = [];
                foreach ($productsQuery as $product) {
                    $products[$product->id] = $product;
                }

                // ✅ 追加：予約済み合計（reservedのみ）を product_id ごとに取得
                $reservationItemsTable = $this->fetchTable('ReservationItems');
                $reservedMap = $reservationItemsTable->sumReservedQuantityByProductIds(array_keys($requestedMap));

                // ✅ 追加：合算上限チェック（予約済み + 今回 <= max_quantity）
                foreach ($requestedMap as $pid => $reqQty) {
                    $p = $products[$pid] ?? null;
                    if (!$p) {
                        $this->Flash->error('不正な商品が含まれています。');
                        return $this->redirect(['action' => 'add']);
                    }

                    if ($p->max_quantity !== null) {
                        $already = $reservedMap[$pid] ?? 0;
                        if ($already + $reqQty > (int)$p->max_quantity) {
                            $remain = max(0, (int)$p->max_quantity - $already);
                            $this->Flash->error('予約数量の上限を超えています。上限を超えて予約を承るのであれば出品管理から同一の商品を追加してください。：' . $p->product_master->name . '（残り ' . $remain . '）');
                            return $this->redirect(['action' => 'add']);
                        }
                    }
                }

                // 合計計算（ここは今まで通り）
                $lines = [];
                $total = 0;

                foreach ($items as $it) {
                    $p = $products[$it['product_id']] ?? null;
                    if (!$p) {
                        $this->Flash->error('不正な商品が含まれています。');
                        return $this->redirect(['action' => 'add']);
                    }

                    $q = (int)$it['quantity'];

                    // ★削除：1予約内だけチェックは不要（合算チェックで担保済み）
                    // if ($p->max_quantity !== null && $q > (int)$p->max_quantity) ...

                    $lineTotal = $p->price * $q;
                    $total += $lineTotal;

                    $lines[] = [$p, $q];
                }

                if (empty($lines)) {
                    $this->Flash->error('商品が不正です。');
                    return $this->redirect(['action' => 'add']);
                }

                // トランザクションで保存
                $conn = $this->Reservations->getConnection();
                $conn->begin();

                try {
                    $reservation = $this->Reservations->newEntity([
                        'business_day_id' => $businessDayId,
                        'source' => 'instagram',
                        'status' => 'reserved',
                        'customer_name' => $data['customer_name'],
                        'phone' => $data['phone'],
                        'email' => $data['email'] ?? null,
                        'note' => $data['note'] ?? null,
                        'total_price' => $total,
                    ]);

                    if (!$this->Reservations->save($reservation)) {
                        throw new \RuntimeException('予約保存失敗');
                    }

                    foreach ($lines as [$p, $q]) {
                        $ri = $reservationItemsTable->newEntity([
                            'reservation_id' => $reservation->id,
                            'product_id' => $p->id,
                            'product_name_at_order' => $p->product_master->name,
                            'price_at_order' => $p->price,
                            'quantity' => $q,
                        ]);

                        if (!$reservationItemsTable->save($ri)) {
                            throw new \RuntimeException('予約商品保存失敗');
                        }
                    }

                    $conn->commit();

                    $this->Flash->success('Instagram予約を登録しました');
                    return $this->redirect(['action' => 'view', $reservation->id]);
                } catch (\Throwable $e) {
                    $conn->rollback();
                    $this->Flash->error('登録に失敗しました。入力内容を確認してください。');
                }
            }
        }

        $this->set(compact('reservation', 'businessDays'));
    }


    public function productsForBusinessDay()
    {
        $this->request->allowMethod(['get']);

        $businessDayId = (int)$this->request->getQuery('business_day_id');

        $list = [];
        if ($businessDayId > 0) {
            $rows = $this->fetchTable('Products')->find()
                ->where([
                    'Products.business_day_id' => $businessDayId,
                    'Products.is_active' => true,
                ])
                ->contain(['ProductMasters'])
                ->orderBy(['Products.id' => 'ASC'])
                ->all();

            foreach ($rows as $p) {
                $list[] = [
                    'id' => $p->id,
                    'name' => $p->product_master->name,
                    'price' => $p->price,
                    'max_quantity' => $p->max_quantity,
                ];
            }
        }

        $this->viewBuilder()
        ->setClassName(JsonView::class)
        ->setOption('serialize', ['products']);

         $this->set('products', $list);
    }



    /**
     * Edit method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reservation = $this->Reservations->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reservation = $this->Reservations->patchEntity($reservation, $this->request->getData());
            if ($this->Reservations->save($reservation)) {
                $this->Flash->success(__('The reservation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reservation could not be saved. Please, try again.'));
        }
        $businessDays = $this->Reservations->BusinessDays->find('list', limit: 200)->all();
        $products = $this->Reservations->Products->find('list', limit: 200)->all();
        $this->set(compact('reservation', 'businessDays', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reservation = $this->Reservations->get($id);
        if ($this->Reservations->delete($reservation)) {
            $this->Flash->success(__('The reservation has been deleted.'));
        } else {
            $this->Flash->error(__('The reservation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function toggleStatus($id = null)
    {
        $this->request->allowMethod(['post']);

        $conn = $this->Reservations->getConnection();
        $conn->begin();

        try {
            // 予約 + 明細を取得
            $reservation = $this->Reservations->get($id, [
                'contain' => ['ReservationItems'],
            ]);

            // reserved <-> canceled を切り替え
            $next = ($reservation->status === 'canceled') ? 'reserved' : 'canceled';

            // canceled -> reserved に戻す時だけ在庫チェック
            if ($reservation->status === 'canceled' && $next === 'reserved') {

                // この予約が「復活」させようとしている数量を product_id ごとに集計
                $restoreMap = []; // product_id => quantity
                foreach ($reservation->reservation_items as $item) {
                    $pid = (int)$item->product_id;
                    $restoreMap[$pid] = ($restoreMap[$pid] ?? 0) + (int)$item->quantity;
                }

                // 明細が無いなら reserved に戻す意味が薄い（運用次第）
                if (empty($restoreMap)) {
                    throw new \RuntimeException('明細が無い予約はreservedに戻せません。');
                }

                // 全ユーザーの予約済み合計（reservedのみ）
                $reservationItems = $this->fetchTable('ReservationItems');
                $reservedMap = $reservationItems->sumReservedQuantityByProductIds(array_keys($restoreMap));

                // max_quantity を見るため products を取得
                $products = $this->fetchTable('Products')->find()
                    ->where(['Products.id IN' => array_keys($restoreMap)])
                    ->contain(['ProductMasters'])
                    ->all()
                    ->indexBy('id')
                    ->toArray();

                // 在庫チェック：reservedAll(他人分) + restore <= max_quantity
                foreach ($restoreMap as $pid => $restoreQty) {
                    $p = $products[$pid] ?? null;
                    if (!$p) {
                        throw new \RuntimeException('不正な商品が含まれています。');
                    }

                    if ($p->max_quantity === null) {
                        continue; // 無制限
                    }

                    $already = $reservedMap[$pid] ?? 0;

                    if ($already + $restoreQty > (int)$p->max_quantity) {
                        $remain = max(0, (int)$p->max_quantity - $already);
                        throw new \RuntimeException(
                            '在庫不足のため予約を復活できません：' . $p->product_master->name . '（残り ' . $remain . '）'
                        );
                    }
                }
            }

            // ステータス更新
            $reservation->status = $next;

            if (!$this->Reservations->save($reservation)) {
                throw new \RuntimeException('ステータス更新に失敗しました');
            }

            $conn->commit();
            $this->Flash->success('ステータスを更新しました：' . $next);
            return $this->redirect(['action' => 'view', $id]);

        } catch (\Throwable $e) {
            $conn->rollback();
            $this->Flash->error($e->getMessage());
            return $this->redirect(['action' => 'view', $id]);
        }
    }


}
