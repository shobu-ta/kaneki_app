<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BusinessDaysFixture
 */
class BusinessDaysFixture extends TestFixture
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
                'business_date' => '2026-02-02',
                'order_deadline' => '2026-02-02 06:25:56',
                'is_active' => 1,
                'created' => '2026-02-02 06:25:56',
                'modified' => '2026-02-02 06:25:56',
            ],
        ];
        parent::init();
    }
}
