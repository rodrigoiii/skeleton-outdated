<?php

namespace App\Http\Middlewares\Auth;

use Framework\BaseMiddleware;
use AuthSlim\Middlewares\GuestMiddlewareTrait;

class GuestMiddleware extends BaseMiddleware
{
    use GuestMiddlewareTrait;
}