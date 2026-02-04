<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateProductMasters extends BaseMigration
{
    /**
     * Change method for the migration.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('product_masters');
        $table
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('base_price', 'integer', [
                'null' => false,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
            ])
            ->create();
    }
}
