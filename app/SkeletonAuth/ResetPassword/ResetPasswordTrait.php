<?php

namespace SkeletonAuth\ResetPassword;

use App\Models\AuthToken;
use App\Models\User;
use App\Requests\ResetPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\ResetPassword\HandlerTrait;

trait ResetPasswordTrait
{
    use HandlerTrait;

    /**
     * Display reset password page
     *
     * @param  Response $response
     * @return Response
     */
    public function getResetPassword()
    {
        return $this->view->render($response, "auth/reset-password.twig");
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
        $new_password = $_request->getParam('new_password');

        $authToken = AuthToken::findByToken($token);
        $payload = json_decode($authToken->getPayload());

        $user = User::find($payload->user_id);
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);

        return $user->save() ?
                $this->resetPasswordSuccess() :
                $this->resetPasswordError();
    }
}
