<?php

namespace AuthSlim\Controllers;

use AuthSlim\Models\User;
use AuthSlim\Models\VerificationToken;
use AuthSlim\Requests\ResetPasswordRequest;
use Slim\Exception\NotFoundException;

trait ResetPasswordControllerTrait
{
    public function getTwigView()
    {
        return $this->container->view;
    }

    public function getLogger()
    {
        return $this->container->logger;
    }

    public function successRedirect($response)
    {
        $this->getFlash()->addMessage('alert-message', "Successfully changing password.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failRedirect($response)
    {
        $this->getFlash()->addMessage('alert-message', "Changing password is not working this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function tokenExpiredRedirect($response)
    {
        $this->getFlash()->addMessage('warning', "Warning! Your token for resetting password is already expired.");
        return $response->withRedirect($this->container->router->pathFor('auth.forgot-password'));
    }

    public function getResetPassword($request, $response, $args)
    {
        $token = $args['token'];

        if (!VerificationToken::tokenExist($token))
        {
            $this->getLogger()->warning("Token for resetting password: Token is not exist.");
            throw new NotFoundException($request, $response);
        }

        $verification_token = VerificationToken::findByToken($token);
        if ($verification_token->isExpired())
        {
            $this->getLogger()->warning("Token for resetting password: Token is already expired.");
            $verification_token->delete();

            return $this->tokenExpiredRedirect($response);
        }

        if ($verification_token->isVerified())
        {
            $this->getLogger()->warning("Token for resetting password: Token is already verified.");
            throw new NotFoundException($request, $response);
        }

        return $this->getTwigView()->render($response, "auth/reset-password.twig", compact('token'));
    }

    public function postResetPassword($request, $response, $args)
    {
        $token = $args['token'];

        if (!(new ResetPasswordRequest($request))->isValid())
        {
            return $response->withRedirect($this->container->router->pathFor('auth.reset-password', compact('token')));
        }

        $verification_token = VerificationToken::findByToken($token);

        // mark the token as verified
        $verification_token->verify();

        $data = $verification_token->getDecryptData();

        $user = User::findByEmail($data->email);

        $is_saved = $user->changePassword($request->getParam('password'));
        return $is_saved ? $this->successRedirect($response) : $this->failRedirect($response);
    }
}