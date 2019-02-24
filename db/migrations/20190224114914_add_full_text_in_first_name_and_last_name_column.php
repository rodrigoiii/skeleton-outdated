<?php

use Phinx\Migration\AbstractMigration;

class AddFullTextInFirstNameAndLastNameColumn extends AbstractMigration
{
    /**
     * [up description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('users')
            ->addIndex(["first_name", "last_name"], ['type' => "fulltext"]);

        $table->save();
    }

    /**
     * [down description]
     *
     * @return void
     */
    public function down()
    {
        $table_exist = $this->hasTable('users');
        if ($table_exist)
        {
            $table = $this->table('users');
            $columns_exist = $table->hasColumn('first_name') && $table->hasColumn('last_name');

            if ($columns_exist)
            {
                $table->removeIndex(["first_name", "last_name"]);
                $table->save();
            }
        }
    }
}
