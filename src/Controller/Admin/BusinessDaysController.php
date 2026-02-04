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
        $businessDays = $this->BusinessDays
        ->find()
        ->order(['business_date' => 'DESC']);

        $this->set(compact('businessDays'));
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
        $businessDay = $this->BusinessDays->get($id, contain: ['Products', 'Reservations']);
        $this->set(compact('businessDay'));
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
}
