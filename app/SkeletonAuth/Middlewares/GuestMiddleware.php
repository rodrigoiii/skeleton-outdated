<?php

namespace App\SkeletonAuth\Middlewares;

use SkeletonAuth\Middlewares\GuestTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestTrait;
}
