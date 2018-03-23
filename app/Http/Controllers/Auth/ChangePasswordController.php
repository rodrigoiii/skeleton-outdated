<?php

namespace App\Http\Controllers\Auth;

use AuthSlim\Controllers\ChangePasswordControllerTrait;
use Framework\BaseController;

class ChangePasswordController extends BaseController
{
	use ChangePasswordControllerTrait;

    public function successRedirect($response)
    {
        $this->flash->addMessage('success', "Successfully changing password.");
        return $response->withRedirect($this->container->router->pathFor('auth.authenticated-home-page'));
    }

    public function failRedirect($response)
    {
        $this->flash->addMessage('danger', "Changing password is not working this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.authenticated-home-page'));
    }
}
