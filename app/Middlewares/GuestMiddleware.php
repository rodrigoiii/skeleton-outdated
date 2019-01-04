<?php

namespace App\Middlewares;

use SkeletonAuth\Middleware\GuestMiddlewareTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}
