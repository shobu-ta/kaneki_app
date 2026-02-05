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
        $query = $this->Reservations->find()
            ->contain(['BusinessDays'])
            ->orderBy(['Reservations.created' => 'DESC']);

        $reservations = $this->paginate($query);

        $this->set(compact('reservations'));
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
