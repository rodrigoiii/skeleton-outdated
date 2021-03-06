<?php

namespace SkeletonAuthApp\Middlewares;

use SkeletonAuth\Traits\Middlewares\User as UserTrait;
use SkeletonCore\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserTrait;
}
