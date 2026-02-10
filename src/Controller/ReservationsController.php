<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Reservations Controller
 *
 * @property \App\Model\Table\ReservationsTable $Reservations
 */
class ReservationsController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        // 客側はログイン不要
        $this->Authentication->allowUnauthenticated(['index', 'view', 'add','start','confirm', 'complete', 'done']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Reservations->find()
            ->contain(['BusinessDays']);
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
        $reservation = $this->Reservations->get($id, contain: ['BusinessDays', 'ReservationItems']);
        $this->set(compact('reservation'));
    }


    

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $session = $this->request->getSession();
        $businessDayId = (int)$session->read('Reservation.business_day_id');
        if ($businessDayId <= 0) {
            $this->Flash->error('営業日を選択してください。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'index']);
        }

        $redirect = $this->redirectIfClosed($businessDayId);
        if ($redirect) {
            return $redirect;
        }

        if (!$session->check('Reservation.items')) {
            $this->Flash->error('商品選択からやり直してください。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // 最低限の手動バリデーション（まずはこれでOK）
            if (empty($data['customer_name']) || empty($data['phone']) || empty($data['email'])) {
                $this->Flash->error('氏名・電話番号・メールアドレスは必須です。');
            } else {
                $session->write('Reservation.customer', [
                    'customer_name' => $data['customer_name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'note' => $data['note'] ?? null,
                ]);
                return $this->redirect(['action' => 'confirm']);
            }
        }
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
        $this->set(compact('reservation', 'businessDays'));
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

    public function start()
    {
        $this->request->allowMethod(['post']);

        $businessDayId = (int)$this->request->getData('business_day_id');
        $qty = (array)$this->request->getData('quantity');

        // 数量が1以上のものだけ抽出
        $items = [];
        foreach ($qty as $productId => $q) {
            $q = (int)$q;
            if ($q > 0) {
                $items[] = ['product_id' => (int)$productId, 'quantity' => $q];
            }
        }

        if ($businessDayId <= 0 || empty($items)) {
            $this->Flash->error('商品と数量を選択してください。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'view', $businessDayId]);
        }

        $session = $this->request->getSession();
        $session->write('Reservation.business_day_id', $businessDayId);
        $session->write('Reservation.items', $items);

        return $this->redirect(['action' => 'add']);
    }

    public function confirm()
    {
        $session = $this->request->getSession();
        $items = (array)$session->read('Reservation.items');
        $businessDayId = (int)$session->read('Reservation.business_day_id');
        $redirect = $this->redirectIfClosed($businessDayId);
        if ($redirect) {
            return $redirect;
        }
        $customer = (array)$session->read('Reservation.customer');

        if (empty($items) || empty($customer)) {
            $this->Flash->error('入力が不足しています。');
            return $this->redirect(['action' => 'add']);
        }

        $productIds = array_column($items, 'product_id');

        $products = $this->fetchTable('Products')->find()
            ->where([
                'Products.id IN' => $productIds,
                'Products.business_day_id' => $businessDayId,
                'Products.is_active' => true,
            ])
            ->contain(['ProductMasters'])
            ->all()
            ->indexBy('id') // idで引けるようにする
            ->toArray();     

        $lines = [];
        $total = 0;

        foreach ($items as $it) {
            $pid = (int)$it['product_id'];
            $p = $products[$pid] ?? null;  
            if (!$p) continue;
            $lineTotal = $p->price * (int)$it['quantity'];
            $total += $lineTotal;

            $lines[] = [
                'name' => $p->product_master->name,
                'price' => $p->price,
                'quantity' => (int)$it['quantity'],
                'line_total' => $lineTotal,
                'product_id' => $p->id,
            ];
        }

        if (empty($lines)) {
            $this->Flash->error('選択商品が不正です。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'view', $businessDayId]);
        }

        $this->set(compact('lines', 'total', 'customer', 'businessDayId'));
    }

    public function complete()
    {
        $this->request->allowMethod(['post']);

        $session = $this->request->getSession();
        $items = (array)$session->read('Reservation.items');
        $businessDayId = (int)$session->read('Reservation.business_day_id');
        $customer = (array)$session->read('Reservation.customer');
        if (empty($items) || empty($customer)) {
            $this->Flash->error('セッションが切れました。最初からやり直してください。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'index']);
        }
        // 営業日締切チェック（改ざん対策で再確認）
        $businessDay = $this->fetchTable('BusinessDays')->get($businessDayId);
        if ($businessDay->order_deadline < new \DateTimeImmutable()) {
            $this->Flash->error('受付終了しています。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'view', $businessDayId]);
        }

        $productIds = array_column($items, 'product_id');

        $products = $this->fetchTable('Products')->find()
            ->where([
                'Products.id IN' => $productIds,
                'Products.business_day_id' => $businessDayId,
                'Products.is_active' => true,
            ])
            ->contain(['ProductMasters'])
            ->all()
            ->indexBy('id')
            ->toArray(); 

        // 合計計算＆上限チェック（簡易：max_quantity >= quantity）
        $lines = [];
        $total = 0;
        foreach ($items as $it) {
        $pid = (int)$it['product_id'];
        $p = $products[$pid] ?? null;  
        if (!$p) continue;

            $q = (int)$it['quantity'];
            if ($p->max_quantity !== null && $q > (int)$p->max_quantity) {
                $this->Flash->error('数量上限を超えています：' . $p->product_master->name);
                return $this->redirect(['controller' => 'BusinessDays', 'action' => 'view', $businessDayId]);
            }

            $lineTotal = $p->price * $q;
            $total += $lineTotal;

            $lines[] = [$p, $q];
        }

        if (empty($lines)) {
            $this->Flash->error('選択商品が不正です。');
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'view', $businessDayId]);
        }

        $reservations = $this->fetchTable('Reservations');
        $reservationItems = $this->fetchTable('ReservationItems');

        $connection = $reservations->getConnection();
        $connection->begin();

        try {
            $reservation = $reservations->newEntity([
                'business_day_id' => $businessDayId,
                'source' => 'web',
                'status' => 'reserved',
                'customer_name' => $customer['customer_name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'note' => $customer['note'] ?? null,
                'total_price' => $total,
            ]);

            if (!$reservations->save($reservation)) {
                throw new \RuntimeException('予約保存失敗');
            }

            foreach ($lines as [$p, $q]) {
                $ri = $reservationItems->newEntity([
                    'reservation_id' => $reservation->id,
                    'product_id' => $p->id, // 出品ID
                    'product_name_at_order' => $p->product_master->name,
                    'price_at_order' => $p->price,
                    'quantity' => $q,
                ]);

                if (!$reservationItems->save($ri)) {
                    throw new \RuntimeException('予約商品保存失敗');
                }
            }

            $connection->commit();

            // セッション削除
            $session->delete('Reservation');

            return $this->redirect(['action' => 'done']);
        } catch (\Throwable $e) {
            $connection->rollback();
            $this->Flash->error('予約処理に失敗しました。もう一度お試しください。');
            return $this->redirect(['action' => 'confirm']);
        }
    }

    public function done()
    {
    }

    private function reservationClosedMessage(): string
    {
        return '予約受付は終了いたしました。ご予約をご希望のお客様は、恐れ入りますがInstagramのメッセージ、または「admin@gmail.com」までお問い合わせください。';
    }

    private function isDeadlinePassed(int $businessDayId): bool
    {
        $businessDay = $this->fetchTable('BusinessDays')->find()
            ->select(['id', 'order_deadline'])
            ->where(['id' => $businessDayId])
            ->first();

        if (!$businessDay) {
            return true; // 不正な営業日IDは通さない
        }

        return $businessDay->order_deadline < new \DateTimeImmutable();
    }

    private function redirectIfClosed(int $businessDayId)
    {
        if ($this->isDeadlinePassed($businessDayId)) {
            $this->Flash->error($this->reservationClosedMessage());
            return $this->redirect(['controller' => 'BusinessDays', 'action' => 'index']);
        }

        return null;
    }



}
