<?php

namespace App\Middlewares;

use SkeletonAuth\Middleware\UserMiddlewareTrait;
use SkeletonCore\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserMiddlewareTrait;
}
