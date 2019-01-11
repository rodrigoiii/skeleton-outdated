<?php

namespace App\SkeletonAuth\Mailers;

use SkeletonAuth\Mailers\ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
