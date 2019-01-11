<?php

namespace App\SkeletonAuth\Mailers;

use SkeletonAuth\Mailers\RegisterVerificationTrait;
use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    use RegisterVerificationTrait;
}
