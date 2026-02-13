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
        $businessDays = $this->BusinessDays->find()
            ->where(['BusinessDays.is_active' => true])
            ->contain([
                'Products' => function ($q) {
                    return $q->where(['Products.is_active' => true])
                            ->contain(['ProductMasters'])
                            ->orderBy(['Products.id' => 'ASC']);
                }
            ])
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
}
