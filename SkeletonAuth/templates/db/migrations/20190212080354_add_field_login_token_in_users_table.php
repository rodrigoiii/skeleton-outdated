<?php


use Phinx\Migration\AbstractMigration;

class AddFieldLoginTokenInUsersTable extends AbstractMigration
{
    /**
     * [down description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table('users')
            ->addColumn('login_token', 'string', ['limit' => 13, 'null' => true]); // uniqid()

        $table->update();
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
            $column_exist = $table->hasColumn('login_token');

            if ($column_exist)
            {
                $table->removeColumn('login_token');
                $table->save();
            }
        }
    }
}
