<?php

namespace SkeletonAuthApp\Mails;

use SkeletonAuth\Traits\Mails\RegisterVerification as RegisterVerificationTrait;
use SkeletonMail\SkeletonMail;

class RegisterVerification extends SkeletonMail
{
    use RegisterVerificationTrait;
}
