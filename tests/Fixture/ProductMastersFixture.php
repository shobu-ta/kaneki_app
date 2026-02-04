<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductMastersFixture
 */
class ProductMastersFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'base_price' => 1,
                'is_active' => 1,
                'created' => '2026-02-04 06:37:35',
                'modified' => '2026-02-04 06:37:35',
            ],
        ];
        parent::init();
    }
}
