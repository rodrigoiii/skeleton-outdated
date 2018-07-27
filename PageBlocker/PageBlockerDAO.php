<?php

namespace PageBlocker;

use PageBlocker\Helper;

class PageBlockerDAO
{
    private $db;
    private $table;
    private $block_time;
    private $attempt_length;

    public function __construct($db_config, $table, $block_time=60*30, $attempt_length=5)
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
        $query = "CREATE TABLE IF NOT EXISTS `{$table}` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `uri` tinytext NOT NULL,
                      `ip_address` varchar(20) NOT NULL,
                      `created_at` datetime NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                    ";

        $this->db->query($query);
    }

    public function getAll()
    {
        $table = $this->getTable();
        $result = $this->db->query("SELECT * FROM {$table}");

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
        $uri = $parse_url['path'];
        $ip = Helper::getUserIp();

        $result = $this->db->query("SELECT * FROM {$table}
                            WHERE `uri`='{$uri}' AND `ip_address`='{$ip}' {$chain_query}");

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

        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = $parse_url['path'];
        $ip = Helper::getUserIp();

        $insert_query = "INSERT INTO `{$table}`(`uri`, `ip_address`, `created_at`)
                        VALUES('{$uri}', '{$ip}', '".date('Y-m-d h:i:s')."')";

        $is_inserted = $this->db->query($insert_query);

        return $is_inserted;
    }

    public function reset()
    {
        $table = $this->getTable();

        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = $parse_url['path'];
        $ip = Helper::getUserIp();

        $delete_query = "DELETE FROM {$table} WHERE `uri`='{$uri}' AND `ip_address`='{$ip}'";
        $is_deleted = $this->db->query($delete_query);

        return $is_deleted;
    }

    public function isAuthorize()
    {
        $is_authorized = true;
        $table = $this->getTable();

        $parse_url = parse_url($_SERVER['REQUEST_URI']);
        $uri = $parse_url['path'];
        $ip = Helper::getUserIp();

        $rows = $this->get("ORDER BY created_at DESC");
        if (!is_null($rows))
        {
            $last_row = $rows[0];

            $created_at = strtotime($last_row->created_at);
            $created_at = strtotime("+".$this->block_time." seconds", $created_at);
            $created_at = date("Y-m-d h:i:s", $created_at);
            $now = date("Y-m-d h:i:s");

            if ($now >= $created_at)
            {
                $this->reset();
                goto authorized;
            }

            dump_die(count($rows));

            \Log::debug(count($rows) ." <= ". $this->attempt_length);

            $is_authorized = count($rows) <= $this->attempt_length;
        }

        authorized:
        return $is_authorized;
    }
}
