<?php

namespace App\Http\Middlewares\Auth;

use App\Models\Auth\User;
use AuthSlim\Auth\Auth;
use AuthSlim\Middlewares\UserMiddlewareTrait;
use Framework\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserMiddlewareTrait;
}