<?php

namespace AuthSlim\Controllers;

use AuthSlim\Models\User;
use AuthSlim\Models\VerificationToken;
use AuthSlim\Notifications\ResetPassword;
use AuthSlim\Requests\ForgotPasswordRequest;
use AuthSlim\Utilities\Helper;

trait ForgotPasswordControllerTrait
{
    public function getTwigView()
    {
        return $this->container->view;
    }

    public function getFlash()
    {
        return $this->container->flash;
    }

    public function getLogger()
    {
        return $this->container->logger;
    }

    public function getForgotPassword($request, $response)
    {
        return $this->getTwigView()->render($response, "auth/forgot-password.twig");
    }

    public function sendResetPasswordEmail($user, $link)
    {
        $email = $user->email;
        $fullname = $user->first_name . " " . $user->last_name;

        return (new ResetPassword(['from@gmail.com' => "Foo Bar"], [$email => $fullname], $link))->sendMail();
    }

    public function successSendEmailResetPassword($response)
    {
        $this->getFlash()->addMessage('alert-message', "Reset password email was sent! Please check your email.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failSendEmailResetPassword($response)
    {
        $this->getFlash()->addMessage('alert-message', "Unable to send email to reset your password this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function postForgotPassword($request, $response)
    {
        if (!(new ForgotPasswordRequest($request))->isValid())
        {
            return $response->withRedirect($this->container->router->pathFor('auth.forgot-password'));
        }

        $email = $request->getParam('email');

        $token = uniqid();

        VerificationToken::create([
            'type' => VerificationToken::TYPE_FORGOT_PASSWORD,
            'token' => $token,
            'data' => VerificationToken::encryptData([
                'email' => $email
            ])
        ]);

        $user = User::findByEmail($request->getParam('email'));
        $link = Helper::baseUrl(trim($this->container->router->pathFor('auth.reset-password', compact('token')), "/"));

        $this->getLogger()->info("Token for forgot password: Reset password page link: {$link}");

        $is_sent = $this->sendResetPasswordEmail($user, $link);

        return $is_sent ? $this->successSendEmailResetPassword($response) : $this->failSendEmailResetPassword($response);
    }
}