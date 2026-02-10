<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Table\ProductMastersTable;
use Cake\Database\Exception\QueryException;

/**
 * ProductMasters Controller (Admin)
 */
class ProductMastersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $productMasters = $this->paginate(
            $this->ProductMasters->find()
                ->orderBy(['genre' => 'ASC', 'name' => 'ASC'])
                ->orderBy(['id' => 'DESC'])
        );
        $genres = ProductMastersTable::GENRES;
        $this->set(compact('productMasters', 'genres'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMaster = $this->ProductMasters->newEmptyEntity();
        $genres = ProductMastersTable::GENRES;

        if ($this->request->is('post')) {
            $productMaster = $this->ProductMasters->patchEntity(
                $productMaster,
                $this->request->getData()
            );

            if ($this->ProductMasters->save($productMaster)) {
                $this->Flash->success('商品を登録しました');

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('登録に失敗しました');
        }

        $this->set(compact('productMaster', 'genres'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin/product Master id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $productMaster = $this->ProductMasters->get($id);
        $genres = ProductMastersTable::GENRES;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $productMaster = $this->ProductMasters->patchEntity(
                $productMaster,
                $this->request->getData()
            );

            if ($this->ProductMasters->save($productMaster)) {
                $this->Flash->success('商品を更新しました');

                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error('更新に失敗しました');
        }

        $this->set(compact('productMaster', 'genres'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Admin/product Master id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $productMaster = $this->ProductMasters->get($id);

        $hasProducts = $this->ProductMasters->Products->exists([
            'product_master_id' => (int)$id,
        ]);

        if ($hasProducts) {
            $this->Flash->error('この商品は出品商品に紐づいているため削除することができません。');

            return $this->redirect(['action' => 'index']);
        }

        try {
            if ($this->ProductMasters->delete($productMaster)) {
                $this->Flash->success('商品マスタを削除しました。');
            } else {
                $this->Flash->error('商品マスタの削除に失敗しました。');
            }
        } catch (QueryException $e) {
            $this->Flash->error('この商品は出品商品に紐づいているため削除することができません。');
        }

        return $this->redirect(['action' => 'index']);
    }
}
