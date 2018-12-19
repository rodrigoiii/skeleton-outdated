<?php

namespace SkeletonAuth\ForgotPassword;

use App\Models\User;
use App\Requests\ForgotPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\ForgotPassword\HandleTrait;

trait ForgotPasswordTrait
{
    use HandleTrait;

    public function getForgotPassword(Response $response)
    {
        return $this->view->render($response, "auth/forgot-password.twig");
    }

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
