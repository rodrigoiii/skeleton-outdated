<?php

namespace App\SkeletonAuth\Mailers;

use SkeletonAuth\Mailer\ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
