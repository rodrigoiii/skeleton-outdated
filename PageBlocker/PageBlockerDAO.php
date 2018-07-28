<?php

namespace PageBlocker;

use PageBlocker\Helper;

class PageBlockerDAO
{
    private $db;
    private $table;
    private $block_time;
    private $attempt_length;

    public function __construct($db_config, $table, $block_time, $attempt_length)
    {
        $this->db = new \mysqli(
            $db_config['host'],
            $db_config['username'],
            $db_config['password'],
            $db_config['database']
        );

        $this->table = $table;
        $this->block_time = $block_time;
        $this->attempt_length = $attempt_length;
    }

    public function getDB()
    {
        return $this->db;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function createTableIfNotExist()
    {
        $table = $this->getTable();
        $create_table_query = "CREATE TABLE IF NOT EXISTS `{$table}` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `uri` tinytext NOT NULL,
                      `ip_address` varchar(20) NOT NULL,
                      `created_at` datetime NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ";

        $result = $this->db->query($create_table_query);

        // invalid mysql query
        if ($result === false) throw new \Exception("Invalid mysql query {$create_table_query}", 1);
    }

    public function getAll()
    {
        $table = $this->getTable();
        $select_query = "SELECT * FROM {$table}";
        $result = $this->db->query($select_query);

        // invalid mysql query
        if ($result === false) throw new \Exception("Invalid mysql query {$select_query}", 1);

        $rows = [];
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_object()) {
                $rows[] = $row;
            }

            $result->free();
        }

        return $rows;
    }

    public function get($chain_query="")
    {
        $table = $this->getTable();

        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = Helper::getURI();
        $ip = Helper::getUserIP();

        $select_query = "SELECT * FROM {$table} WHERE `uri`='{$uri}' AND `ip_address`='{$ip}' {$chain_query}";
        $result = $this->db->query($select_query);

        // invalid mysql query
        if ($result === false) throw new \Exception("Invalid mysql query: {$select_query}'", 1);

        $rows = [];
        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_object()) {
                $rows[] = $row;
            }

            $result->free();
        }

        return $rows;
    }

    public function add()
    {
        $table = $this->getTable();

        $uri = Helper::getURI();
        $ip = Helper::getUserIP();

        $insert_query = "INSERT INTO `{$table}`(`uri`, `ip_address`, `created_at`)
                        VALUES('{$uri}', '{$ip}', '".date('Y-m-d h:i:s')."')";

        $result = $this->db->query($insert_query);

        // invalid mysql query
        if ($result === false) throw new \Exception("Invalid mysql query {$insert_query}", 1);
    }

    public function reset()
    {
        $table = $this->getTable();

        $uri = Helper::getURI();
        $ip = Helper::getUserIP();

        $delete_query = "DELETE FROM {$table} WHERE `uri`='{$uri}' AND `ip_address`='{$ip}'";
        $result = $this->db->query($delete_query);

        // invalid mysql query
        if ($result === false) throw new \Exception("Invalid mysql query {$delete_query}", 1);
    }

    public function isAuthorize()
    {
        $is_authorized = true;
        $table = $this->getTable();

        $uri = Helper::getURI();
        $ip = Helper::getUserIP();

        $rows = $this->get("ORDER BY created_at DESC");

        if (!empty($rows))
        {
            $last_row = $rows[0];

            $created_at = strtotime($last_row->created_at);
            $created_at = strtotime("+".$this->block_time." seconds", $created_at);
            $created_at = date("Y-m-d h:i:s", $created_at);
            $now = date("Y-m-d h:i:s");

            if ($now >= $created_at)
            {
                $this->reset();
            }
            else
            {
                $is_authorized = count($rows) < $this->attempt_length;
            }
        }

        return $is_authorized;
    }
}
