<?php

namespace App\Http\Middlewares\Auth;

use App\Models\Auth\User;
use AuthSlim\Auth\Auth;
use AuthSlim\Middlewares\UserMiddlewareTrait;
use Framework\BaseMiddleware;

class UserMiddleware extends BaseMiddleware
{
    use UserMiddlewareTrait;

    public function authenticated()
    {
        $this->getTwigView()->getEnvironment()->addGlobal('auth_user', [
            'get'  => User::find(Auth::user()->id),
            'check'  => Auth::check(),
        ]);
    }
}