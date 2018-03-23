<?php

namespace App\Http\Middlewares\Auth;

use Framework\BaseMiddleware;
use AuthSlim\Middlewares\ValidToLoginMiddlewareTrait;

class ValidToLoginMiddleware extends BaseMiddleware
{
    use ValidToLoginMiddlewareTrait;
}