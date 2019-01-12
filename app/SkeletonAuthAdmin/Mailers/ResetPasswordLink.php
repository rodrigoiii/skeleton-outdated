<?php

namespace App\SkeletonAuthAdmin\Mailers;

use SkeletonAuthAdmin\Mailers\ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
