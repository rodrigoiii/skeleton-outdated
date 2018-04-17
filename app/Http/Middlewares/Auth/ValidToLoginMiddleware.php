<?php

namespace App\Http\Middlewares\Auth;

use AuthSlim\Middlewares\ValidToLoginMiddlewareTrait;
use FrameworkCore\BaseMiddleware;

class ValidToLoginMiddleware extends BaseMiddleware
{
    use ValidToLoginMiddlewareTrait;
}