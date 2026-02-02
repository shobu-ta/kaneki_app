<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 */
class ProductsFixture extends TestFixture
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
                'business_day_id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'price' => 1,
                'max_quantity' => 1,
                'is_active' => 1,
                'created' => '2026-02-02 06:26:56',
                'modified' => '2026-02-02 06:26:56',
            ],
        ];
        parent::init();
    }
}
