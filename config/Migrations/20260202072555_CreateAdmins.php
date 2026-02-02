<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateAdmins extends BaseMigration
{
    /**
     * Create the admins table.
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('admins');
        $table->addColumn('email', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->create();
    }
}
