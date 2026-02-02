<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateBusinessDays extends BaseMigration
{
    /**
     * Create the business_days table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('business_days');
        $table->addColumn('business_date', 'date', ['null' => false])
              ->addColumn('order_deadline', 'datetime', ['null' => false])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->create();
    }
}
