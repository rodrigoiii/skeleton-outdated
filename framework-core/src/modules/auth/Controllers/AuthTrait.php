<?php

namespace Framework\Auth\Controllers;

use Framework\Auth\Auth\Auth;
use Framework\Auth\Bridge;

trait AuthTrait
{
    public function getInvalidMessage()
    {
        return "Invalid email and password.";
    }

    public function successRedirect($response)
    {
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    public function logoutRedirect($response)
    {
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function getLogin($request, $response)
    {
        return $this->twigView->render($response, "_auth/login.twig");
    }

    public function postLogin($request, $response)
    {
        $AuthAttempt = Bridge::model("AuthAttempt");

        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (!Auth::attempt($email, $password))
        {
            $AuthAttempt::add($email, $request->getUri()->getPath());

            $this->flash->addMessage('danger', $this->getInvalidMessage());
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }

        $AuthAttempt::reset();
        return $this->successRedirect($response);
    }

    public function logout($request, $response)
    {
        Auth::logout();
        return $this->logoutRedirect($response);
    }
}