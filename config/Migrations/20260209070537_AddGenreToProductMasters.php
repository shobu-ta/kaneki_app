<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddGenreToProductMasters extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $this->table('product_masters')
            ->addColumn('genre', 'string', [
                'limit' => 50,
                'null' => false,
                'default' => '蒸しパン',
                'after' => 'name',
            ])
            ->update();
    }

}
