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
            ->addColumn("by_id", "integer")
            ->addColumn("to_id", "integer")
            ->addColumn("is_read_by", "boolean", ['default' => 0])
            ->addColumn("is_read_to", "boolean", ['default' => 0])
            ->addTimestamps();

        $table->create();

        $table->addForeignKey("by_id", "users", "id")
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
                ->dropForeignKey(["by_id", "to_id"])
                ->save();

            $this->dropTable("notifications");
        }
    }
}
