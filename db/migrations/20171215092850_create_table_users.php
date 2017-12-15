<?php

use Phinx\Migration\AbstractMigration;

class CreateTableUsers extends AbstractMigration
{
    public function up ()
    {
        $table = $this->table('users')
            ->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addTimestamps();

        $table->create();
    }

    public function down ()
    {
        $exist = $this->hasTable('users');
        if ($exist)
        {
            $this->dropTable('users');
        }
    }
}
