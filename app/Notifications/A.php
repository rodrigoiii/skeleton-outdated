<?php

namespace App\Notifications;

use Mail;

class A
{
    private $mail;

    public function __construct()
    {
        $this->mail = new Mail;
    }

    public function sendMail()
    {
        return (new Mail)
            ->subject("")
            ->from(['email' => "name"])
            ->to(['email' => "name"])
            ->view("path-to-twig-file");
    }
}