<?php

use Phinx\Migration\AbstractMigration;

class CreateTableChatStatuses extends AbstractMigration
{
    /**
     * [up description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('chat_statuses')
            ->addColumn('status', 'enum', ['values' => ["online", "offline"]])
            ->addColumn('user_id', 'integer')
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
        $table_exist = $this->hasTable('chat_statuses');
        if ($table_exist)
        {
            $this->dropTable('chat_statuses');
        }
    }
}
