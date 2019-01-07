<?php

namespace App\SkeletonAuthApp\Middlewares;

use SkeletonAuth\Middleware\GuestMiddlewareTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}
