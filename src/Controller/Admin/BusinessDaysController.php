<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * BusinessDays Controller
 *
 * @property \App\Model\Table\BusinessDaysTable $BusinessDays
 */
class BusinessDaysController extends AppController
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

        $query = $this->BusinessDays->find()
            ->orderBy(['business_date' => 'DESC']);

        // 年・月が指定されていれば絞り込み
        if ($year > 0 && $month >= 1 && $month <= 12) {
            $start = sprintf('%04d-%02d-01', $year, $month);
            $end = (new \DateTimeImmutable($start))->modify('first day of next month')->format('Y-m-d');

            $query->where([
                'BusinessDays.business_date >=' => $start,
                'BusinessDays.business_date <'  => $end,
            ]);
        } elseif ($year > 0) {
            // 年だけ指定されている場合
            $start = sprintf('%04d-01-01', $year);
            $end = sprintf('%04d-01-01', $year + 1);

            $query->where([
                'BusinessDays.business_date >=' => $start,
                'BusinessDays.business_date <'  => $end,
            ]);
        }

        // 20件ずつページネーション
        $this->paginate = [
            'limit' => 20,
        ];

        $businessDays = $this->paginate($query);

        // フォーム用：現在の選択値をテンプレに渡す
        $this->set(compact('businessDays', 'year', 'month'));
    }

    

    /**
     * View method
     *
     * @param string|null $id Business Day id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // 営業日自体
        $businessDay = $this->BusinessDays->get($id);

        // 予約商品を集計（reservedのみ）
        $reservationItems = $this->fetchTable('ReservationItems');

        $summary = $reservationItems->find()
            ->select([
                'product_id' => 'ReservationItems.product_id',
                'product_name' => 'ReservationItems.product_name_at_order',
                'unit_price' => 'ReservationItems.price_at_order',
                'total_qty' => $reservationItems->find()->func()->sum('ReservationItems.quantity'),
                'total_amount' => $reservationItems->find()->func()->sum(
                    'ReservationItems.price_at_order * ReservationItems.quantity'
                ),
            ])
            ->innerJoinWith('Reservations', function ($q) use ($id) {
                return $q->where([
                    'Reservations.business_day_id' => $id,
                    'Reservations.status' => 'reserved',
                ]);
            })
            // product_id単位で集計（同一商品でも名前を変えたくないならこれが安定）
            ->group([
                'ReservationItems.product_id',
                'ReservationItems.product_name_at_order',
                'ReservationItems.price_at_order',
            ])
            ->order(['product_name' => 'ASC'])
            ->all();

        // 営業日全体の合計（予約数・合計金額）
        $reservations = $this->fetchTable('Reservations');
        $totals = $reservations->find()
            ->select([
                'count' => $reservations->find()->func()->count('*'),
                'total_price' => $reservations->find()->func()->sum('Reservations.total_price'),
            ])
            ->where([
                'Reservations.business_day_id' => $id,
                'Reservations.status' => 'reserved',
            ])
            ->first();

        $this->set(compact('businessDay', 'summary', 'totals'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // ① 空のエンティティを作る
        $businessDay = $this->BusinessDays->newEmptyEntity();

        // ② フォーム送信時（POST）の処理
        if ($this->request->is('post')) {

            // ③ フォームデータをエンティティに詰める
            $businessDay = $this->BusinessDays->patchEntity(
                $businessDay,
                $this->request->getData()
            );

            // ④ 保存
            if ($this->BusinessDays->save($businessDay)) {
                $this->Flash->success('営業日を登録しました');

                // ⑤ 一覧に戻る
                return $this->redirect(['action' => 'index']);
            }

            // ⑥ 失敗時
            $this->Flash->error('営業日の登録に失敗しました');
        }

        // ⑦ テンプレートへ渡す
        $this->set(compact('businessDay'));
    }


    /**
     * Edit method
     *
     * @param string|null $id Business Day id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        // ① 対象の営業日を取得
        $businessDay = $this->BusinessDays->get($id);

        // ② フォーム送信時
        if ($this->request->is(['patch', 'post', 'put'])) {

            // ③ フォームデータを反映
            $businessDay = $this->BusinessDays->patchEntity(
                $businessDay,
                $this->request->getData()
            );

            // ④ 保存
            if ($this->BusinessDays->save($businessDay)) {
                $this->Flash->success('営業日を更新しました');

                return $this->redirect(['action' => 'index']);
            }

            // ⑤ 失敗時
            $this->Flash->error('営業日の更新に失敗しました');
        }

        // ⑥ テンプレートへ渡す
        $this->set(compact('businessDay'));
    }


    /**
     * Delete method
     *
     * @param string|null $id Business Day id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $businessDay = $this->BusinessDays->get($id);
        if ($this->BusinessDays->delete($businessDay)) {
            $this->Flash->success(__('The business day has been deleted.'));
        } else {
            $this->Flash->error(__('The business day could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Toggle active status method
     */
    public function toggle($id = null)
    {
        $this->request->allowMethod(['post']);

        $day = $this->BusinessDays->get($id);

        $day->is_active = !$day->is_active;

        if ($this->BusinessDays->save($day)) {
            $this->Flash->success('状態を更新しました');
        } else {
            $this->Flash->error('更新に失敗しました');
        }

        return $this->redirect($this->referer());
    }

}
