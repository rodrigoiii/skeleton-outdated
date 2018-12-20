<?php

namespace App\Middlewares;

use SkeletonAuth\MiddlewareTrait\GuestMiddlewareTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}
