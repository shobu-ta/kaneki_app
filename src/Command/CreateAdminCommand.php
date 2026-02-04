<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class CreateAdminCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $admins = $this->fetchTable('Admins');

        $admin = $admins->newEntity([
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        if ($admins->save($admin)) {
            $io->out('✅ Admin created successfully');
        } else {
            $io->err('❌ Failed to create admin');
            debug($admin->getErrors());
        }
    }
}

