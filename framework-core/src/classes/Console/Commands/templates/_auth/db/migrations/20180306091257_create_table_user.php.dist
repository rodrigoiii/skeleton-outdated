<?php

use Phinx\Migration\AbstractMigration;

class CreateTableUser extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users')
            ->addColumn('first_name', 'string', ['limit' => 25])
            ->addColumn('last_name', 'string', ['limit' => 25])
            ->addColumn('email', 'string', ['limit' => 60])
            ->addColumn('password', 'string', ['limit' => 60])
            ->addColumn('auth_token', 'string', ['limit' => 25, 'null' => true])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $exist = $this->hasTable('users');
        if ($exist)
        {
            $this->dropTable('users');
        }
    }
}