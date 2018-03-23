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
        $this->flash->addMessage('danger', "Registration is not working this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function successSendEmailConfirmation($response)
    {
        $this->getFlash()->addMessage('success', "Email confirmation was sent! Please check your email.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function failSendEmailConfirmation($response)
    {
        $this->getFlash()->addMessage('danger', "Unable to send email to verify your account this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function getRegister($request, $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }
}
