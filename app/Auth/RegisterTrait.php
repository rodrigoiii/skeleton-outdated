<?php

namespace SkeletonAuth;

use App\Mailers\RegisterVerification;
use App\Models\AuthToken;
use App\Models\User;
use App\Requests\RegisterRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RegisterTrait
{
    public function getRegister(Response $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }

    public function postRegister(RegisterRequest $_request, Response $response)
    {
        // $inputs = $_request->getParams();
        // $files = $_request->getUploadedFiles();

        // $result = User::create([
        //     'picture' => upload($files['picture']),
        //     'first_name' => $inputs['first_name'],
        //     'last_name' => $inputs['last_name'],
        //     'email' => $inputs['email'],
        //     'password' => password_hash($inputs['password'], PASSWORD_DEFAULT)
        // ]);

        // flash($result instanceof User,
        //     ['success' => "Success registered!"],
        //     ['danger' => "Registration not working properly this time. Please try again later."]
        // );

        // return $response->withRedirect($this->router->pathFor('auth.login'));

        $inputs = $_request->getParams();
        $files = $_request->getUploadedFiles();

        if (config('auth.registration.is_verification_enabled'))
        {
            $fullname = $inputs['first_name'] . " " . $inputs['last_name'];

            $authToken = AuthToken::createRegisterType(json_encode([
                'picture' => upload($files['picture']),
                'first_name' => $inputs['first_name'],
                'last_name' => $inputs['last_name'],
                'email' => $inputs['email'],
                'password' => password_hash($inputs['password'], PASSWORD_DEFAULT)
            ]));

            if ($authToken instanceof AuthToken)
            {
                $link = base_url("auth/register/verify/" . $authToken->token);

                // send email contains link
                $this->sendEmailLink($fullname, $inputs['email'], $link);

                $this->flash->addMessage('success', "Success! Check your email and click the link to verify your account.");
                return $response->withRedirect($this->router->pathFor('auth.login'));
            }
        }
        else
        {
            // save user info
            $user = $this->saveUserInfo($inputs, $files);

            if ($user instanceof User)
            {
                $this->flash->addMessage('success', "Successfully register!");
                if (config('auth.registration.is_log_in_after_register'))
                {
                    Auth::loggedInByUserId($user->id);
                    return $response->withRedirect($this->router->pathFor('auth.home'));
                }
                else
                {
                    return $response->withRedirect($this->router->pathFor('auth.login'));
                }
            }
        }

        $this->flash->addMessage('error', "Cannot register this time. Please try again later.");
        return $response->withRedirect($this->router->pathFor('auth.register'));
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

    public function accessProvidedLink()
    {

    }

    public function saveUserInfo(array $inputs, array $files)
    {
        $result = User::create([
            'picture' => upload($files['picture']),
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'email' => $inputs['email'],
            'password' => password_hash($inputs['password'], PASSWORD_DEFAULT)
        ]);

        return $result;
    }

    public function loginTheUser()
    {

    }

    public function redirectToAuthenticatedHomePage()
    {

    }

    public function redirectToLoginPage()
    {

    }
}
