<?php

namespace AuthSlim\Controllers;

use AuthSlim\Models\AuthAttempt;
use AuthSlim\Auth\Auth;

trait AuthControllerTrait
{
    public function getTwigView()
    {
        return $this->container->view;
    }

    public function getFlash()
    {
        return $this->container->flash;
    }

    public function successRedirect($response)
    {
        $this->getFlash()->addMessage('success', "Successfully Login.");
        return $response->withRedirect($this->container->router->pathFor('auth.authenticated-home-page'));
    }

    public function failRedirect($response)
    {
        $this->getFlash()->addMessage('danger', "Invalid email and password.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function logoutRedirect($response)
    {
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function getLogin($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/login.twig");
    }

    public function postLogin($request, $response)
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (!Auth::attempt($email, $password))
        {
            AuthAttempt::add($email);

            return $this->failRedirect($response);
        }

        AuthAttempt::reset();
        return $this->successRedirect($response);
    }

    public function logout($request, $response)
    {
        Auth::logout();
        return $this->logoutRedirect($response);
    }
}
