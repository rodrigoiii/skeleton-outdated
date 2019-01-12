<?php

namespace App\SkeletonAuthAdmin\Mailers;

use SkeletonAuthAdmin\Mailers\RegisterVerificationTrait;
use SkeletonMailer\Mailer;

class RegisterVerification extends Mailer
{
    use RegisterVerificationTrait;
}
