<?php

namespace App\SkeletonAuthAdmin\Middlewares;

use SkeletonAuthAdmin\Middlewares\GuestTrait;
use SkeletonCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestTrait;
}
