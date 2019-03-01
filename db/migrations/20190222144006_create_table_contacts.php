<?php

use Phinx\Migration\AbstractMigration;

class CreateTableContacts extends AbstractMigration
{
    /**
     * [up description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('contacts')
            ->addColumn('contact_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('is_accepted', 'boolean', ['default' => 0])
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
        $table_exist = $this->hasTable('contacts');
        if ($table_exist)
        {
            $this->dropTable('contacts');
        }
    }
}
