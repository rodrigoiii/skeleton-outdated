<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\Controllers\ForgotPasswordControllerTrait;
use Framework\BaseController;

class ForgotPasswordController extends BaseController
{
	use ForgotPasswordControllerTrait;

    public function successSendEmailResetPassword($response)
    {
        $this->getFlash()->addMessage('success', "Reset password email was sent! Please check your email.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function failSendEmailResetPassword($response)
    {
        $this->getFlash()->addMessage('danger', "Unable to send email to reset your password this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
