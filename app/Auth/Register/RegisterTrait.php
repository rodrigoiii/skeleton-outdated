<?php

namespace SkeletonAuth\Register;

use App\Mailers\RegisterVerification;
use App\Models\AuthToken;
use App\Models\User;
use App\Requests\RegisterRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Register\HandleTrait;
use Slim\Exception\NotFoundException;

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
                $recipient_nums = $this->sendVerificationLink($authToken);

                return $recipient_nums > 0 ?
                        $this->sendEmailLinkSuccess($response) :
                        $this->sendEmailLinkError($response);
            }

            return $this->saveAuthTokenError($response);
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

            return $this->registerSuccessCallback($response);
        }

        return $this->registerErrorCallback($response);
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
                            $this->loginTheUser($user->id);
                            $this->flash->addMessage('success', "Success! Your account has been verified.");
                            return $this->redirectToAuthenticatedPage($response);
                        }
                        else
                        {
                            $this->flash->addMessage('success', "Success! Your account has been verified, please login using your new account.");
                            return $this->redirectToUnAuthenticatedPage($response);
                        }
                    }
                    else
                    {
                        \Log::error("Error: saveUserInfo method return not instance of User");
                    }
                }
            }
        }

        throw new NotFoundException($request, $response);
        exit;
    }

    public function redirectToAuthenticatedPage(Response $response)
    {
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    public function redirectToUnAuthenticatedPage(Response $response)
    {
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
