<?php

namespace App\Mailers;

use SkeletonAuth\MailerTrait\RegisterVerificationTrait;
use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    use RegisterVerificationTrait;
}
