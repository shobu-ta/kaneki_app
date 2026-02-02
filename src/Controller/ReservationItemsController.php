<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ReservationItems Controller
 *
 * @property \App\Model\Table\ReservationItemsTable $ReservationItems
 */
class ReservationItemsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->ReservationItems->find()
            ->contain(['Reservations', 'Products']);
        $reservationItems = $this->paginate($query);

        $this->set(compact('reservationItems'));
    }

    /**
     * View method
     *
     * @param string|null $id Reservation Item id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reservationItem = $this->ReservationItems->get($id, contain: ['Reservations', 'Products']);
        $this->set(compact('reservationItem'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reservationItem = $this->ReservationItems->newEmptyEntity();
        if ($this->request->is('post')) {
            $reservationItem = $this->ReservationItems->patchEntity($reservationItem, $this->request->getData());
            if ($this->ReservationItems->save($reservationItem)) {
                $this->Flash->success(__('The reservation item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reservation item could not be saved. Please, try again.'));
        }
        $reservations = $this->ReservationItems->Reservations->find('list', limit: 200)->all();
        $products = $this->ReservationItems->Products->find('list', limit: 200)->all();
        $this->set(compact('reservationItem', 'reservations', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reservation Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reservationItem = $this->ReservationItems->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $reservationItem = $this->ReservationItems->patchEntity($reservationItem, $this->request->getData());
            if ($this->ReservationItems->save($reservationItem)) {
                $this->Flash->success(__('The reservation item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reservation item could not be saved. Please, try again.'));
        }
        $reservations = $this->ReservationItems->Reservations->find('list', limit: 200)->all();
        $products = $this->ReservationItems->Products->find('list', limit: 200)->all();
        $this->set(compact('reservationItem', 'reservations', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reservation Item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reservationItem = $this->ReservationItems->get($id);
        if ($this->ReservationItems->delete($reservationItem)) {
            $this->Flash->success(__('The reservation item has been deleted.'));
        } else {
            $this->Flash->error(__('The reservation item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
