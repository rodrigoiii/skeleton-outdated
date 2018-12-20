<?php

namespace App\Mailers;

use SkeletonAuth\MailerTrait\ResetPasswordLinkTrait;
use SkeletonMailer\Mailer;

class ResetPasswordLink extends Mailer
{
    use ResetPasswordLinkTrait;
}
