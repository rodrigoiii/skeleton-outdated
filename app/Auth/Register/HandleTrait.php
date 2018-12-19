<?php

namespace SkeletonAuth\Register;

use App\Mailers\RegisterVerification;
use App\Models\AuthToken;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;

trait HandleTrait
{
    public function sendVerificationLink(AuthToken $authToken)
    {
        $inputs = json_decode($authToken->getPayload());

        $fullname = $inputs->first_name . " " . $inputs->last_name;
        $link = base_url("auth/register/verify/" . $authToken->token);

        $registerVerification = new RegisterVerification($fullname, $inputs->email, $link);
        $recipient_nums = $registerVerification->send();

        return $recipient_nums;
    }

    public function sendEmailLinkSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Success! Check your email and click the link to verify your account.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function sendEmailLinkError(Response $response)
    {
        \Log::error("Error: Sending email contains verification link not working properly.");

        return $this->registerError($response);
    }

    public function saveUserInfo(array $inputs)
    {
        $user = User::create($inputs);
        return $user;
    }

    public function registerError(Response $response)
    {
        $this->flash->addMessage('error', "Registration not working properly this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.register'));
    }

    public function registerSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Successfully Register!");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function saveAuthTokenError($response)
    {
        \Log::error("Error: Saving Auth token fail!");

        return $this->registerError($response);
    }

    public function verifySuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your account has been verified. Please login using your new account.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function verifyError(Request $request, Response $response)
    {
        throw new NotFoundException($request, $response);
        exit;
    }
}
