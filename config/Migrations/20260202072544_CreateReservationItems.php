<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateReservationItems extends BaseMigration
{
    /**
     * Create the reservation_items table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('reservation_items');
        $table->addColumn('reservation_id', 'integer', ['null' => false])
              ->addColumn('product_id', 'integer', ['null' => false])
              ->addColumn('product_name_at_order', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('price_at_order', 'integer', ['null' => false])
              ->addColumn('quantity', 'integer', ['null' => false])
              ->addForeignKey('reservation_id', 'reservations', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
