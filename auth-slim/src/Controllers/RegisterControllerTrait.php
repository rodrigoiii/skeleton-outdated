<?php

namespace AuthSlim\Controllers;

use AuthSlim\Models\User;
use AuthSlim\Models\VerificationToken;
use AuthSlim\Notifications\ConfirmRegistration;
use AuthSlim\Requests\UserRequest;
use AuthSlim\Utilities\Helper;
use Slim\Exception\NotFoundException;

trait RegisterControllerTrait
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

    public function enableVerificationToken()
    {
        return false;
    }

    public function successRedirect($response)
    {
        $this->getFlash()->addMessage('alert-message', "Successfully Registered.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failRedirect($response)
    {
        $this->getFlash()->addMessage('alert-message', "Registration is not working this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function tokenExpiredRedirect($response)
    {
        $this->getFlash()->addMessage('warning', "Warning! Your token for registration is already expired.");
        return $response->withRedirect($this->container->router->pathFor('auth.register'));
    }

    public function successSendEmailConfirmation($response)
    {
        $this->getFlash()->addMessage('alert-message', "Email confirmation was sent! Please check your email.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failSendEmailConfirmation($response)
    {
        $this->getFlash()->addMessage('alert-message', "Unable to send email to verify your account this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function successConfirmUserRedirect($verification_token, $response)
    {
        $data = $verification_token->getDecryptData();

        // save data
        $is_saved = $this->saveInfo([
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        if ($is_saved)
        {
            $this->getFlash()->addMessage('success', "Success! Your account is now verified. You can now login with your account.");
        }
        else
        {
            return $this->failConfirmUserRedirect($response);
        }

        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function failConfirmUserRedirect($response)
    {
        $this->getFlash()->addMessage('danger', "Sorry! You cannot verify you account this time. Please try again later.");
        return $response->withRedirect($this->container->router->pathFor('auth.login'));
    }

    public function getRegister($request, $response)
    {
        return $this->view->render($response, "register.twig");
    }

    public function postRegister($request, $response)
    {
        if (!(new UserRequest($request))->isValid())
        {
            return $response->withRedirect($this->container->router->pathFor('auth.register'));
        }

        if ($this->enableVerificationToken())
        {
            $input = $request->getParams();

            $token = uniqid();

            VerificationToken::create([
                'type' => VerificationToken::TYPE_REGISTER,
                'token' => $token,
                'data' => VerificationToken::encryptData([
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'email' => $input['email'],
                    'password' => password_hash($input['password'], PASSWORD_DEFAULT)
                ])
            ]);

            $link = Helper::baseUrl(trim($this->container->router->pathFor('auth.verify.register-user', compact('token')), "/"));
            $this->getLogger()->info("Token for register: Verification link: {$link}");

            // send confirmation email
            $is_sent = $this->sendConfirmationEmail($input, $link);
            return $is_sent ? $this->successSendEmailConfirmation($response) : $this->failSendEmailConfirmation($response);
        }
        else
        {
            $input = $request->getParams();

            $is_saved = $this->saveInfo([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            ]);

            return $is_saved ? $this->successRedirect($response) : $this->failRedirect($response);
        }
    }

    public function saveInfo($data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return $user instanceof User;
    }

    public function verifyUser($request, $response, $args)
    {
        $token = $args['token'];

        if (!VerificationToken::tokenExist($token))
        {
            $this->getLogger()->warning("Token for register: Token is not exist.");
            throw new NotFoundException($request, $response);
        }

        $verification_token = VerificationToken::findByToken($token);
        if ($verification_token->isExpired())
        {
            $this->getLogger()->warning("Token for register: Token is already expired.");
            $verification_token->delete();

            return $this->tokenExpiredRedirect($response);
        }

        if ($verification_token->isVerified())
        {
            $this->getLogger()->warning("Token for register: Token is already verified.");
            throw new NotFoundException($request, $response);
        }

        return $verification_token->verify() ? $this->successConfirmUserRedirect($verification_token, $response) : $this->failConfirmUserRedirect($response);
    }

    public function sendConfirmationEmail($input, $link)
    {
        $fullname = $input['first_name'] . " " . $input['last_name'];
        $is_sent = (new ConfirmRegistration(['from@gmail.com' => "Foo Bar"], [$input['email'] => $fullname], $link))->sendMail();

        return $is_sent;
    }
}