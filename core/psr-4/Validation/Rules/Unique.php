<?php

namespace App\Validation\Rules;

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Rules\AbstractRule;

class Unique extends AbstractRule
{
    public $table;
    public $column;
    public $exclude_id;

    public function __construct($table, $column, int $exclude_id = -1)
    {
        $this->table = $table;
        $this->column = $column;
        $this->exclude_id = $exclude_id;
    }

    public function validate($input)
    {
        $query = DB::table($this->table)->where($this->column, $input);

        if (!empty($this->exclude_id))
            $query = $query->where('id', "<>", $this->exclude_id);

        return $query->get()->count() === 0;
    }
}