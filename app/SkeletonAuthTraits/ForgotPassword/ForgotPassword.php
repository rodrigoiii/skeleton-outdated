<?php

namespace SkeletonAuth\ForgotPassword;

use App\Models\AuthToken;
use App\Models\User;
use App\Requests\ForgotPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\ForgotPassword\HandlerTrait;

trait ForgotPasswordTrait
{
    use HandlerTrait;

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

        if ($recipient_num > 0)
        {
            \Log::info("Info: " . $user->getFullName() . " attempt to reset his/her password.");
            $this->sendEmailLinkSuccess($response);
        }
        else
        {
            $this->sendEmailLinkError($response);
        }
    }
}
