<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateProducts extends BaseMigration
{
    /**
     * Create the products table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('products');
        $table->addColumn('business_day_id', 'integer', ['null' => false])
              ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('price', 'integer', ['null' => false])
              ->addColumn('max_quantity', 'integer', ['null' => true])
              ->addColumn('is_active', 'boolean', ['default' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('business_day_id', 'business_days', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();
    }
}
