<?php


use Phinx\Migration\AbstractMigration;

class CreateTableAuthTokens extends AbstractMigration
{
    /**
     * [up description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('auth_tokens')
            ->addColumn('token', 'string', ['limit' => 60]) // todo: make this field unique
            ->addColumn('is_used', 'boolean')
            ->addTimestamps();

        $table->create();
    }

    /**
     * [down description]
     *
     * @return void
     */
    public function down()
    {
        $table_exist = $this->hasTable('auth_tokens');
        if ($table_exist)
        {
            $this->dropTable('auth_tokens');
        }
    }
}
