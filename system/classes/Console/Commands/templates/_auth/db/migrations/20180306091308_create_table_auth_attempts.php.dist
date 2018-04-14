<?php

use Phinx\Migration\AbstractMigration;

class CreateTableAuthAttempts extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('auth_attempts')
            ->addColumn('email', 'string', ['limit' => 60])
            ->addColumn('request_uri', 'string', ['limit' => 25])
            ->addColumn('ip_address', 'string', ['limit' => 25])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $exist = $this->hasTable('auth_attempts');
        if ($exist)
        {
            $this->dropTable('auth_attempts');
        }
    }
}