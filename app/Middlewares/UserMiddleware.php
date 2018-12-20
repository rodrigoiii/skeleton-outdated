<?php

namespace App\Middlewares;

use SkeletonAuth\MiddlewareTrait\UserMiddlewareTrait;
use SkeletonCore\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserMiddlewareTrait;
}
