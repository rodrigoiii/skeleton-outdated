<?php

namespace App\SkeletonAuthAdmin\Middlewares;

use SkeletonAuthAdmin\Middlewares\UserTrait;
use SkeletonCore\BaseMiddleware;

class AdminMiddleware extends BaseMiddleware
{
    use UserTrait;
}
