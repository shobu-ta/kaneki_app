<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($businessDayId)
    {
        $products = $this->Products->find()
            ->where(['business_day_id' => $businessDayId])
            ->contain(['ProductMasters'])
            ->orderBy(['Products.id' => 'ASC']);
        $businessDay = $this->Products->BusinessDays->get($businessDayId);

        $this->set(compact('products', 'businessDayId', 'businessDay'));
    }



    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($businessDayId)
    {
        $product = $this->Products->newEmptyEntity();

        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity(
                $product,
                $this->request->getData()
            );
            $product->business_day_id = $businessDayId;

            if ($this->Products->save($product)) {
                $this->Flash->success('出品商品を追加しました');
                return $this->redirect(['action' => 'index', $businessDayId]);
            }

            $this->Flash->error('登録に失敗しました');
        }

        $productMasters = $this->Products->ProductMasters
            ->find()
            ->where(['ProductMasters.is_active' => true])
            ->order(['ProductMasters.id' => 'ASC'])
            ->all();

        $this->set(compact('product', 'productMasters', 'businessDayId'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $product = $this->Products->get($id, ['contain' => ['BusinessDays']]);
        $businessDay = $product->business_day;

        $reservationItems = $this->fetchTable('ReservationItems');

        // ★この出品が予約に含まれている件数（予約明細の行数）
        $reservedItemCount = $reservationItems->find()
            ->where(['ReservationItems.product_id' => $product->id])
            ->count();

        // ★この出品が含まれる予約件数（予約IDのユニーク数）
        $reservedReservationCount = $reservationItems->find()
            ->select(['reservation_id'])
            ->where(['ReservationItems.product_id' => $product->id])
            ->distinct(['reservation_id'])
            ->count();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity(
                $product,
                $this->request->getData()
            );

            if ($this->Products->save($product)) {
                $this->Flash->success('出品内容を更新しました');
                return $this->redirect([
                    'action' => 'index',
                    $product->business_day_id,
                ]);
            }

            $this->Flash->error('更新に失敗しました');
        }

        $productMasters = $this->Products->ProductMasters
            ->find('list')
            ->where(['is_active' => true]);

        $this->set(compact('product', 'productMasters', 'reservedReservationCount', 'reservedItemCount', 'businessDay'));
    }
}