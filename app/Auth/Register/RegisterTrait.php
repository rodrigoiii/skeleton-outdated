<?php

namespace SkeletonAuth\Register;

use App\Models\AuthToken;
use App\Models\User;
use App\Requests\RegisterRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Auth;
use SkeletonAuth\Register\HandleTrait;

trait RegisterTrait
{
    use HandleTrait;

    public function getRegister(Response $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }

    public function postRegister(RegisterRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();
        $files = $_request->getUploadedFiles();

        // upload picture and pass the path
        $picture = upload($files['picture']);

        $data = [
            'picture' => $picture,
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'email' => $inputs['email'],
            'password' => password_hash($inputs['password'], PASSWORD_DEFAULT)
        ];

        if (! config('auth.registration.is_verification_enabled'))
        {
            // create token register type
            $authToken = AuthToken::createRegisterType(json_encode($data));

            if ($authToken instanceof AuthToken)
            {
                // send verification link
                $recipient_num = $this->sendVerificationLink($authToken);

                return $recipient_num > 0 ?
                        $this->sendEmailLinkSuccess($response) :
                        $this->sendEmailLinkError($response, "Error: Sending email contains verification link fail.");
            }

            return $this->saveAuthTokenError($response, "Error: Saving Auth token fail!");
        }

        // else
        $user = $this->saveUserInfo($data);

        if ($user instanceof User)
        {
            if (config('auth.is_log_in_after_register'))
            {
                // login user automatically
                Auth::loggedInByUserId($user->getId());
            }

            return $this->registerSuccess($response);
        }

        return $this->registerError($response, "Error: Saving user info fail!");
    }

    /**
     * Verify if the token is valid
     *
     * @param  Request  $request
     * @param  Response $response
     * @param  string   $token
     * @return void|Response
     */
    public function verify(Request $request, Response $response, $token)
    {
        $authToken = AuthToken::findByToken($token);

        $error_message = "";

        // check if token exist
        if (! is_null($authToken))
        {
            // check if token not expired
            if (! $authToken->isTokenExpired(config('auth.registration.register_token_expiration')))
            {
                // check if token is not already used
                if (! $authToken->isUsed())
                {
                    $authToken->markTokenAsUsed();

                    // save user info
                    $user = $this->saveUserInfo(json_decode($authToken->getPayload(), true));

                    if ($user instanceof User)
                    {
                        if (config('auth.registration.is_log_in_after_register'))
                        {
                            // login user automatically
                            Auth::loggedInByUserId($user->getId());
                        }

                        return $this->verifySuccess($response);
                    }

                    $error_message = "Error: Saving user info fail!";
                }
                else
                {
                    $error_message = "Warning: Token " . $authToken->token . " is already used!";
                }
            }
            else
            {
                $error_message = "Warning: Token " . $authToken->token . " is already expired!";
            }
        }
        else
        {
            $error_message = "Warning: Token " . $authToken->token . " is not exist!";
        }

        return $this->verifyError($request, $response, $error_message);
    }
}
