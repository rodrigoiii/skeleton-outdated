<?php

namespace App\Http\Middlewares\Auth;

use AuthSlim\Middlewares\GuestMiddlewareTrait;
use FrameworkCore\BaseMiddleware;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}