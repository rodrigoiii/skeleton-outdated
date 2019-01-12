<?php

use Phinx\Migration\AbstractMigration;

class AddIsAdminColumnInUsersTable extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users')
            ->addColumn('is_admin', 'boolean', ['default' => 0, 'after' => "password"]);

        $table->update();
    }

    public function down()
    {
        $table_exist = $this->hasTable('users');
        if ($table_exist)
        {
            $table = $this->table('users');
            $column_exist = $table->hasColumn('is_admin');

            if ($column_exist)
            {
                $table->removeColumn('is_admin');
                $table->save();
            }
        }
    }
}
