<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AlterProductsForProductMasters extends BaseMigration
{
    /**
     * Change method.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('products');

        // ※ ① product_master_id を追加
        $table->addColumn('product_master_id', 'integer', [
            'null' => false,
            'after' => 'business_day_id',
        ]);

        // ※ ② 外部キー追加
        $table->addForeignKey(
            'product_master_id',
            'product_masters',
            'id',
            [
                'delete' => 'RESTRICT',
                'update' => 'NO_ACTION',
            ]
        );

        // ※ ③ name カラム削除（商品名はマスタへ）
        $table->removeColumn('name');

        $table->update();
    }
}
