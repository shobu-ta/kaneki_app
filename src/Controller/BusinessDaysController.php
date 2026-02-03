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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $businessDays = $this->BusinessDays->find('all')
            ->contain(['Products'])
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
            'contain' => ['Products'],
        ]);
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
