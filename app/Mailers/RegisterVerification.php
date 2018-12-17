<?php

namespace App\Mailers;

use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    public function __construct($receiver_name, $receiver_email, $link)
    {
        $this->subject("Register Verification");
        $this->from(['foo@bar.com' => "Skeleton Auth"]);
        $this->to([$receiver_email => $receiver_name]);
        $this->message('Click this <a href="{$link}">link</a> to verify the account');

        /**
         * You can use the source file and make it template of the email
         * Just make sure the source file is in the resources/views/emails folder.
         */
        // $this->messageSourceFile("sample-email.twig", ['name' => "Foo Bar"]);
    }
}
