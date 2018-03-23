<?php

namespace AuthSlim\Notifications;

use NotificationSlim\Mail;
use NotificationSlim\NotificationInterface;

class ResetPassword implements NotificationInterface
{
    protected $from;
    protected $to;
    protected $link;

    public function __construct(array $from, array $to, $link)
    {
        $this->from = $from;
        $this->to = $to;
        $this->link = $link;
    }

    public function sendMail()
    {
        $from_email = key($this->from);
        $from_name = $this->from[$from_email];

        $to_email = key($this->to);
        $to_name = $this->to[$to_email];

        return (new Mail)
            ->subject("Reset Password") // email subject
            ->from([$from_email => $from_name]) // email => full name
            ->to([$to_email => $to_name]) // email => full name
            ->view("reset-password.twig", ['link' => $this->link]); // this is relative at view_path options
    }
}