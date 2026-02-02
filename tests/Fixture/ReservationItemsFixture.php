<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ReservationItemsFixture
 */
class ReservationItemsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'reservation_id' => 1,
                'product_id' => 1,
                'product_name_at_order' => 'Lorem ipsum dolor sit amet',
                'price_at_order' => 1,
                'quantity' => 1,
            ],
        ];
        parent::init();
    }
}
