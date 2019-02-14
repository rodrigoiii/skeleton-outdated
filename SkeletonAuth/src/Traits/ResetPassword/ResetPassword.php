<?php

namespace SkeletonAuth\ResetPassword;

use App\SkeletonAuth\Models\AuthToken;
use App\SkeletonAuth\Models\User;
use App\SkeletonAuth\Requests\ResetPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;

trait ResetPassword
{
    use Handler;

    /**
     * Display reset password page
     *
     * @param  Response $response
     * @return Response
     */
    public function getResetPassword(Request $request, Response $response, $token)
    {
        if ($this->checkToken($token))
        {
            return $this->view->render($response, "auth/reset-password.twig", compact('token'));
        }

        throw new NotFoundException($request, $response);
        exit;
    }

    /**
     * Post data
     *
     * @param  ResetPasswordRequest $_request
     * @param  Response $response
     * @param  string   $token
     * @return Response
     */
    public function postResetPassword(ResetPasswordRequest $_request, Response $response, $token)
    {
        if ($this->checkToken($token))
        {
            $new_password = $_request->getParam('new_password');

            $authToken = AuthToken::findResetPasswordToken($token);
            $payload = json_decode($authToken->getPayload());

            $user = User::find($payload->user_id);
            $user->password = password_hash($new_password, PASSWORD_DEFAULT);

            if ($user->save())
            {
                \Log::info("Info: " . $user->getFullName() . " successfully reset his/her password.");

                $authToken->markTokenAsUsed();
                return $this->resetPasswordSuccess($response);
            }

            return $this->resetPasswordError($response, $token);
        }

        throw new NotFoundException($_request, $response);
        exit;
    }

    /**
     * Check token if valid to use
     *
     * @param  string $token
     * @return boolean
     */
    private function checkToken($token)
    {
        $authToken = AuthToken::findResetPasswordToken($token);

        // check if token exist
        if (! is_null($authToken))
        {
            $is_token_expired = config('auth.modules.reset_password.token_expiration') == false ? false : $authToken->isExpired(config('auth.modules.reset_password.token_expiration'));

            // check if token not expired
            if (!$is_token_expired)
            {
                // check if token is not already used
                if (! $authToken->isUsed())
                {
                    // save user info
                    $payload = json_decode($authToken->getPayload());

                    $user = User::find($payload->user_id);

                    return $user instanceof User;
                }
                else
                {
                    \Log::error("Warning: Token {$token} is already used!");
                }
            }
            else
            {
                \Log::error("Warning: Token {$token} is already expired!");
            }
        }
        else
        {
            \Log::error("Warning: Token {$token} is not exist!");
        }

        return false;
    }
}
