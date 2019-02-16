<?php

namespace SkeletonAuth\Traits\Mailers;

trait ResetPasswordLink
{
    /**
     * Sending email for reset password configuration.
     *
     * @param string $receiver_name
     * @param string $receiver_email
     * @param string $link
     */
    public function __construct($receiver_name, $receiver_email, $link)
    {
        $this->subject("Reset Password");
        $this->from(['foo@bar.com' => "Skeleton Auth"]);
        $this->to([$receiver_email => $receiver_name]);
        $this->message('Click this <a href="'.$link.'">link</a> to reset your password.');
    }
}
