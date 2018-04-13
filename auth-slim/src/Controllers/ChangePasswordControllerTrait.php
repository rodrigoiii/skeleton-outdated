<?php

namespace AuthSlim\Controllers;

use AuthSlim\Auth\Auth;
use AuthSlim\Requests\ChangePasswordRequest;

trait ChangePasswordControllerTrait
{
    public function getTwigView()
    {
        return $this->container->view;
    }

    public function getFlash()
    {
        return $this->container->flash;
    }

    public function getChangePassword($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/change-password.twig");
    }

    public function successRedirect($response)
    {
        $this->getFlash()->addMessage('success', "Successfully changing password.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failRedirect($response)
    {
        $this->getFlash()->addMessage('danger', "Changing password is not working this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function postChangePassword($request, $response)
    {
        if (!(new ChangePasswordRequest($request))->isValid())
        {
            return $response->withRedirect($this->container->router->pathFor('auth.change-password'));
        }

        $user = Auth::user();
        $is_saved = $user->changePassword($request->getParam('new_password'));
        return $is_saved ? $this->successRedirect($response) : $this->failRedirect($response);
    }
}