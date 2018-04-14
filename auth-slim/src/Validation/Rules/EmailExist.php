<?php

namespace AuthSlim\Validation\Rules;

use AuthSlim\Models\User;
use Respect\Validation\Rules\AbstractRule;

class EmailExist extends AbstractRule
{
    public $exclude_ids;

    /**
     * Exclude the provided ids for thie rule
     * @param [string] $exclude_ids Comma separated values.
     */
    public function __construct($exclude_ids = "")
    {
        $this->exclude_ids = $exclude_ids;
    }

    public function validate($input)
    {
        $user = User::findByEmail($input);

        if (!is_null($user))
        {
            if (!empty($this->exclude_ids))
            {
                return !in_array($user->id, explode(",", $this->exclude_ids));
            }

            return true;
        }

        return false;
    }
}