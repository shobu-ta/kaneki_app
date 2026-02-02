<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateReservations extends BaseMigration
{
    /**
     * Create the reservations table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('reservations');
        $table->addColumn('business_day_id', 'integer', ['null' => false])
              ->addColumn('source', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('status', 'string', ['limit' => 20, 'default' => 'reserved'])
              ->addColumn('customer_name', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('phone', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('email', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('total_price', 'integer', ['null' => false])
              ->addColumn('note', 'text', ['null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('business_day_id', 'business_days', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();
    }
}
