<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\Controllers\ResetPasswordControllerTrait;
use Framework\BaseController;

class ResetPasswordController extends BaseController
{
    use ResetPasswordControllerTrait;

    public function successRedirect($response)
    {
        $this->flash->addMessage('success', "Successfully changing password.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failRedirect($response)
    {
        $this->flash->addMessage('danger', "Changing password is not working this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
