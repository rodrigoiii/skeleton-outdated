<?php

namespace SkeletonAuth\ForgotPassword;

use App\SkeletonAuth\Models\AuthToken;
use App\SkeletonAuth\Models\User;
use App\SkeletonAuth\Requests\ForgotPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;

trait ForgotPassword
{
    use Handler;

    /**
     * Display forgot password page
     *
     * @param  Response $response
     * @return Response
     */
    public function getForgotPassword(Response $response)
    {
        return $this->view->render($response, "auth/forgot-password.twig");
    }

    /**
     * Post data
     *
     * @param  ForgotPasswordRequest $_request
     * @param  Response              $response
     * @return Response
     */
    public function postForgotPassword(ForgotPasswordRequest $_request, Response $response)
    {
        $user = User::findByEmail($_request->getParam('email'));

        // create token register type
        $authToken = AuthToken::createResetPasswordType(json_encode(['user_id' => $user->getId()]));

        $recipient_num = $this->sendResetPasswordLink($authToken);

        return $recipient_num > 0 ?
            $this->sendEmailLinkSuccess($response) :
            $this->sendEmailLinkError($response);
    }
}
