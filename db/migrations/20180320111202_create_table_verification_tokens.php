<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateTableVerificationTokens extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('verification_tokens')
            ->addColumn('type', 'enum', ['values' => ["register", "reset-password"]])
            ->addColumn('token', 'string', ['limit' => 25])
            ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_TINY])
            ->addColumn('is_verified', 'boolean', ['default' => 0])
            ->addTimestamps();

        $table->create();
    }

    public function down()
    {
        $exist = $this->hasTable('verification_tokens');
        if ($exist)
        {
            $this->dropTable('verification_tokens');
        }
    }
}
