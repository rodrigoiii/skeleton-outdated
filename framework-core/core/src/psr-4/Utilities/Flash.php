<?php

namespace Framework\Utilities;

use Slim\Flash\Messages;

class Flash
{
    private $flash;

    public function __construct()
    {
        $this->flash = new Messages;
    }

    public function addMessage($is_passed, array $pass_message, array $fail_message)
    {
        if ($is_passed)
        {
            $key = key($pass_message);
            $this->flash->addMessage($key, $pass_message[$key]);
        }
        else
        {
            $key = key($fail_message);
            $this->flash->addMessage($key, $fail_message[$key]);
        }
    }
}