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
            ->order(['Reservations.id' => 'DESC']);

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

    //URLで渡される予約ID（例：/admin/reservations/update-items/4 の 4）を受け取る
    public function updateItems($id = null)
    {
        $this->request->allowMethod(['post']);

        $reservation = $this->Reservations->get($id, [
            'contain' => ['ReservationItems'],
        ]);

        $qtyMap = (array)$this->request->getData('qty'); // フォームから送られてきた数量の配列を受け取る　例：qty[12]=3（明細ID12の数量を3にする）
        $deleteMap = (array)$this->request->getData('delete'); //フォームから送られてきた削除チェックの配列を受け取る　例：delete[12]=1（明細ID12を削除する）

        $reservationItems = $this->fetchTable('ReservationItems');//ReservationItemsTable を取得して、明細を更新/削除できるようにする

        $conn = $this->Reservations->getConnection();//DB接続（コネクション）を取得
        $conn->begin();//トランザクション開始　ここから先で失敗したら rollback() で全部なかったことにできる

        try {
            // ここから「成功させたい処理」を書くブロック（失敗したら catch に行く）
            $items = $reservationItems->find()//この予約に紐づく明細を DBから改めて取り直す　画面に表示されていたものが古い/改ざんされた等を避ける意図もある
                ->where(['reservation_id' => $reservation->id])
                ->all();

            $newTotal = 0;//新しい合計金額をここに足していくため、最初は0
            $remainCount = 0;//明細が何行残ったか数える（全部削除されたか判断するため）

            foreach ($items as $item) { //予約の明細1行ずつ処理するループ開始
                $itemId = (int)$item->id; //明細行のIDを数値にして変数へ

                // その明細行に「削除チェック」が付いていたらDBから削除してこの明細の処理はここで終了（continue で次の明細へ）
                if (!empty($deleteMap[$itemId])) {
                    $reservationItems->delete($item);
                    continue;
                }

                // 数量がフォームから送られてきたならそれを使う　送られてきてなければ、今の数量をそのまま使う（現状維持）
                $newQty = isset($qtyMap[$itemId]) ? (int)$qtyMap[$itemId] : (int)$item->quantity;

                // 数量が0以下なら「削除扱い」にして行を消す そして次の明細へ（UIが簡単になる設計）
                if ($newQty <= 0) {
                    $reservationItems->delete($item);
                    continue;
                }

                $item->quantity = $newQty; //明細行の数量を新しい数量に更新
                //DBに保存できたかチェック　失敗なら例外を投げて catch に飛ばす（トランザクションをロールバックするため
                if (!$reservationItems->save($item)) {
                    throw new \RuntimeException('明細の更新に失敗しました');
                }

                $remainCount++; //この明細は残ったので、残数を1増やす
                $newTotal += ((int)$item->price_at_order) * $newQty; //明細の小計（単価×数量）を合計に足し込む price_at_order は「注文時価格」なので、後から価格が変わっても履歴が保てる
            }

            // 明細が0行なら予約をキャンセル扱いにする　合計は0にする　明細が残っていれば計算した合計金額に更新する
            if ($remainCount === 0) {
                $reservation->status = 'canceled';
                $reservation->total_price = 0;
            } else {
                $reservation->total_price = $newTotal;
            }
            //合計金額やステータス変更を reservations に保存 失敗なら例外→catchへ
            if (!$this->Reservations->save($reservation)) {
                throw new \RuntimeException('予約の更新に失敗しました');
            }
            //ここまで成功したので、トランザクションを確定（DBに正式反映
            $conn->commit();

            $this->Flash->success('予約内容を更新しました');
            return $this->redirect(['action' => 'view', $reservation->id]);
        //途中で何か失敗したらここに来る（save失敗・delete失敗・例外など全部）
        } catch (\Throwable $e) {
            $conn->rollback();//トランザクション中の変更を全部取り消す（明細更新だけ反映、などが起きない）
            $this->Flash->error('更新に失敗しました');
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

        // 営業日セレクト（有効のみ）
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

            // 最低限チェック
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

                // 合計計算 + 上限チェック（簡易）
                $lines = [];
                $total = 0;

                foreach ($items as $it) {
                    $p = $products[$it['product_id']] ?? null;
                    if (!$p) {
                        $this->Flash->error('不正な商品が含まれています。');
                        return $this->redirect(['action' => 'add']);
                    }

                    $q = (int)$it['quantity'];

                    // 上限（max_quantity）チェック：今回は「1予約内だけ」チェック
                    if ($p->max_quantity !== null && $q > (int)$p->max_quantity) {
                        $this->Flash->error('数量上限を超えています：' . $p->product_master->name);
                        return $this->redirect(['action' => 'add']);
                    }

                    $lineTotal = $p->price * $q;
                    $total += $lineTotal;

                    $lines[] = [$p, $q];
                }

                if (empty($lines)) {
                    $this->Flash->error('商品が不正です。');
                    return $this->redirect(['action' => 'add']);
                }

                // トランザクションで保存
                $reservationItemsTable = $this->fetchTable('ReservationItems');
                $conn = $this->Reservations->getConnection();
                $conn->begin();

                try {
                    $reservation = $this->Reservations->newEntity([
                        'business_day_id' => $businessDayId,
                        'source' => 'instagram',   // ★固定
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
                            'product_id' => $p->id, // 出品(products)ID
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

        $reservation = $this->Reservations->get($id);

        $next = ($reservation->status === 'canceled') ? 'reserved' : 'canceled';
        $reservation->status = $next;

        if ($this->Reservations->save($reservation)) {
            $this->Flash->success('ステータスを更新しました：' . $next);
        } else {
            $this->Flash->error('ステータス更新に失敗しました');
        }

        return $this->redirect(['action' => 'view', $id]);
    }

}
