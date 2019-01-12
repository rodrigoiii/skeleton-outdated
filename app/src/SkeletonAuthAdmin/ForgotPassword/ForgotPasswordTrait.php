<?php

namespace SkeletonAuthAdmin\ForgotPassword;

use App\SkeletonAuthAdmin\Models\Admin;
use App\SkeletonAuthAdmin\Requests\ForgotPasswordRequest;
use App\SkeletonAuth\Models\AuthToken;
use Psr\Http\Message\ResponseInterface as Response;

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
        return $this->view->render($response, "auth-admin/forgot-password.twig");
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
        $user = Admin::findByEmail($_request->getParam('email'));

        // create token register type
        $authToken = AuthToken::createResetPasswordType(json_encode(['user_id' => $user->getId()]));

        $recipient_num = $this->sendResetPasswordLink($authToken);

        return $recipient_num > 0 ?
            $this->sendEmailLinkSuccess($response) :
            $this->sendEmailLinkError($response);
    }
}
