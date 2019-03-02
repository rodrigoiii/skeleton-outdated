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
        $table = $this->table("contacts")
            ->addColumn("user_id", "integer")
            ->addColumn("owner_id", "integer")
            ->addTimestamps();

        $table->create();

        $table->addForeignKey("user_id", "users", "id")
            ->addForeignKey("owner_id", "users", "id")
            ->save();
    }

    /**
     * [down description]
     *
     * @return void
     */
    public function down()
    {
        $table_exist = $this->hasTable("contacts");
        if ($table_exist)
        {
            $table = $this->table("contacts")
                ->dropForeignKey(["user_id", "owner_id"])
                ->save();

            $this->dropTable("contacts");
        }
    }
}
