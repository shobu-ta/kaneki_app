<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * BusinessDays Controller
 *
 * @property \App\Model\Table\BusinessDaysTable $BusinessDays
 */
class BusinessDaysController extends AppController
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
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $businessDays = $this->BusinessDays->find('all')
            ->contain(['Products.ProductMasters'])
            ->where(['BusinessDays.is_active' => true])
            ->orderBy(['business_date' => 'ASC']);

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
        $businessDay = $this->BusinessDays->get($id, [
            'contain' => [
                'Products' => function ($q) {
                    return $q->where(['Products.is_active' => true])
                            ->contain(['ProductMasters'])
                            ->order(['Products.id' => 'ASC']);
                }
            ]
        ]);
        if ($businessDay->order_deadline < new \DateTimeImmutable()) {
            $this->Flash->error('予約受付は終了いたしました。ご予約をご希望のお客様は、恐れ入りますがInstagramのメッセージ、または「admin@gmail.com」までお問い合わせください。');

            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('businessDay'));
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $businessDay = $this->BusinessDays->newEmptyEntity();
        if ($this->request->is('post')) {
            $businessDay = $this->BusinessDays->patchEntity($businessDay, $this->request->getData());
            if ($this->BusinessDays->save($businessDay)) {
                $this->Flash->success(__('The business day has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The business day could not be saved. Please, try again.'));
        }
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
        $businessDay = $this->BusinessDays->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $businessDay = $this->BusinessDays->patchEntity($businessDay, $this->request->getData());
            if ($this->BusinessDays->save($businessDay)) {
                $this->Flash->success(__('The business day has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The business day could not be saved. Please, try again.'));
        }
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
