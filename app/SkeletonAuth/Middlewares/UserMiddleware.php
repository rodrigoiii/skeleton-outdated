<?php

namespace App\SkeletonAuth\Middlewares;

use SkeletonAuth\Middlewares\UserTrait;
use SkeletonCore\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserTrait;
}
