<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\Controllers\RegisterControllerTrait;
use Framework\BaseController;

class RegisterController extends BaseController
{
	use RegisterControllerTrait;

    public function enableVerificationToken()
    {
        return true;
    }

    public function successRedirect($response)
    {
        $this->flash->addMessage('success', "Successfully Registered.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function failRedirect($response)
    {
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function getRegister($request, $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }
}
