<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

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
        $genres = \App\Model\Table\ProductMastersTable::GENRES;
        $this->set(compact('productMasters', 'genres'));
    }

    /**
     * View method
     *
     * @param string|null $id Admin/product Master id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
   


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productMaster = $this->ProductMasters->newEmptyEntity();

        $genres = [
        '蒸しパン' => '蒸しパン',
        'シフォンケーキ' => 'シフォンケーキ',
        'パウンドケーキ' => 'パウンドケーキ',
        'そのほか' => 'そのほか',
        ];

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

        $genres = [
        '蒸しパン' => '蒸しパン',
        'シフォンケーキ' => 'シフォンケーキ',
        'パウンドケーキ' => 'パウンドケーキ',
        'そのほか' => 'そのほか',
        ];

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

        if ($this->ProductMasters->delete($productMaster)) {
            $this->Flash->success('商品マスタを削除しました。');
        } else {
            $this->Flash->error('商品マスタの削除に失敗しました。');
        }

        return $this->redirect(['action' => 'index']);
    }

    




}
