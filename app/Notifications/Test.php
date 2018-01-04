<?php

namespace App\Notifications;

use Mail;

class Test
{
    public function __construct()
    {

    }

    public function sendMail()
    {
        return (new Mail)
            ->subject("")
            ->from(['email' => "name"])
            ->to(['email' => "name"])
            ->view("emails/path-to-twig-file");
    }
}