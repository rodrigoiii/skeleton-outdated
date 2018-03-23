<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\Controllers\AuthControllerTrait;
use Framework\BaseController;

class AuthController extends BaseController
{
    use AuthControllerTrait;

    public function successRedirect($response)
    {
        $this->flash->addMessage('success', "Successfully Login.");
        return $response->withRedirect($this->router->pathFor('auth.authenticated-page'));
    }

    public function failRedirect($response)
    {
        $this->flash->addMessage('danger', "Invalid email and password.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function getLogin($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/login.twig");
    }
}