<?php

namespace SkeletonAuthApp\Middlewares;

use SkeletonAuth\Traits\Middlewares\Guest as GuestTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestTrait;
}
