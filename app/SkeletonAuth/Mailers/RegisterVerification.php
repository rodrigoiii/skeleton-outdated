<?php

namespace App\SkeletonAuthApp\Mailers;

use SkeletonAuth\Mailer\RegisterVerificationTrait;
use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    use RegisterVerificationTrait;
}
