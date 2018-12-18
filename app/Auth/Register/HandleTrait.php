<?php

namespace SkeletonAuth\Register;

use App\Models\AuthToken;
use Psr\Http\Message\ResponseInterface as Response;

trait HandleTrait
{
    public function verificationEnabled(array $inputs, Response $response)
    {
        $authToken = AuthToken::createRegisterType(json_encode($inputs));

        if ($authToken instanceof AuthToken)
        {
            $fullname = $inputs['first_name'] . " " . $inputs['last_name'];
            $link = base_url("auth/register/verify/" . $authToken->token);

            // send email contains link
            $recipient_nums = $this->sendEmailLink($fullname, $inputs['email'], $link);

            return $recipient_nums > 0 ? $this->sendEmailLinkSuccess($response) : $this->sendEmailLinkError($response);
        }

        $this->flash->addMessage('error', "Registration not working properly this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.register'));
    }

    public function verificationDisabled(array $inputs, Response $response)
    {
        // save user info
        $user = $this->saveUserInfo($inputs);

        if ($user instanceof User)
        {
            return config('auth.registration.is_log_in_after_register') ?
                        $this->logInEnabled($user->getId(), $response) :
                        $this->logInDisabled($response);
        }

        \Log::error("Error: saveUserInfo method return not instance of User");

        $this->flash->addMessage('error', "Registration not working properly this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.register'));
    }

    public function logInEnabled($user_id, Response $response)
    {
        $this->flash->addMessage('success', "Successfully register!");

        $this->loginTheUser($user_id);
        return $this->redirectToAuthenticatedPage($response);
    }

    public function logInDisabled(Response $response)
    {
        $this->flash->addMessage('success', "Successfully register! Please login using your new account.");
        return $this->redirectToUnAuthenticatedPage($response);
    }

    public function sendEmailLink($name, $email, $link)
    {
        $registerVerification = new RegisterVerification($name, $email, $link);
        $number_of_recipient = $registerVerification->send();

        if ($number_of_recipient > 0)
        {
            \Log::info("Successfully sent register verification to {$name}.");
        }

        return $number_of_recipient;
    }

    public function sendEmailLinkSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Success! Check your email and click the link to verify your account.");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function sendEmailLinkError(Response $response)
    {
        \Log::error("Error: Sending email contains verification link not working properly.");

        $this->flash->addMessage('error', "Registration not working properly this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.register'));
    }
}
