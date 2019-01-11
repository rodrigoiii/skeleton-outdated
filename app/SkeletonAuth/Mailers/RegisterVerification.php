<?php

namespace App\SkeletonAuth\Mailers;

use SkeletonAuth\Mailer\RegisterVerificationTrait;
use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    use RegisterVerificationTrait;
}
