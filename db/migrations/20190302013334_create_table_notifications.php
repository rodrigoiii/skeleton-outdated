<?php

use Phinx\Migration\AbstractMigration;

class CreateTableNotifications extends AbstractMigration
{
    /**
     * [up description]
     *
     * @return void
     */
    public function up()
    {
        $table = $this->table("notifications")
            ->addColumn("type", "enum", ['values' => ["accepted", "requested"]])
            ->addColumn("from_id", "integer")
            ->addColumn("to_id", "integer")
            ->addColumn("is_read", "boolean", ['default' => 0])
            ->addTimestamps();

        $table->create();

        $table->addForeignKey("from_id", "users", "id")
            ->addForeignKey("to_id", "users", "id")
            ->save();
    }

    /**
     * [down description]
     *
     * @return void
     */
    public function down()
    {
        $table_exist = $this->hasTable("notifications");
        if ($table_exist)
        {
            $table = $this->table("notifications")
                ->dropForeignKey(["from_id", "to_id"])
                ->save();

            $this->dropTable("notifications");
        }
    }
}
