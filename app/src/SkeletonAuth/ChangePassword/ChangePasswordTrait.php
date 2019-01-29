<?php

namespace SkeletonAuth\ChangePassword;

use App\SkeletonAuth\Auth;
use App\SkeletonAuth\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;

trait ChangePasswordTrait
{
    use HandlerTrait;

    /**
     * Display change password page
     *
     * @param  Response $response
     * @return Response
     */
    public function getChangePassword(Response $response)
    {
        return $this->view->render($response, "auth/change-password.twig");
    }

    /**
     * Post data
     *
     * @param  ChangePasswordRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function postChangePassword(ChangePasswordRequest $_request, Response $response)
    {
        $new_password = $_request->getParam('new_password');

        $user = Auth::user();
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);

        return $user->save() ?
                $this->changePasswordSuccess($response) :
                $this->changePasswordError($response);
    }
}