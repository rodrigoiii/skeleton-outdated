<?php

namespace AuthSlim\Controllers;

use AuthSlim\Auth\Auth;
use AuthSlim\Requests\AccountDetailRequest;
use AuthSlim\Requests\ChangePasswordRequest;

trait AccountDetailControllerTrait
{
    public function getTwigView()
    {
        return $this->container->view;
    }

    public function getFlash()
    {
        return $this->container->flash;
    }

    public function index($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/account-detail/index.twig");
    }

    public function edit($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/account-detail/edit.twig");
    }

    public function update($request, $response)
    {
        if (!(new AccountDetailRequest($request))->isValid())
        {
            return $response->withRedirect($this->container->router->pathFor('auth.account-detail.update'));
        }

        $user = Auth::user();
        $input = $request->getParams();
        $new_fname = $input['first_name'];
        $new_lname = $input['last_name'];
        $new_email = $input['email'];
        $new_password = $input['new_password'];

        $has_change = $user->changeAccountDetail($new_fname, $new_lname, $new_email, $new_password);
        return $has_change ? $this->successRedirect($response) : $this->failRedirect($response);
    }

    public function successRedirect($response)
    {
        $this->getFlash()->addMessage('success', "Successfully change info.");
        return $response->withRedirect($this->container->router->pathFor('auth.authenticated-home-page'));
    }

    public function failRedirect($response)
    {
        $this->getFlash()->addMessage('info', "No info changed.");
        return $response->withRedirect($this->container->router->pathFor('auth.authenticated-home-page'));
    }
}