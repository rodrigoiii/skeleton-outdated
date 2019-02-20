<?php

namespace SkeletonAuthApp\Mails;

use SkeletonAuth\Traits\Mails\ResetPasswordLink as ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
