<?php

namespace App\Mailers;

use SkeletonAuth\Mailer\ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
