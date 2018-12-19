<?php

namespace SkeletonAuth\ChangePassword;

use App\Auth\Auth;
use App\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\ChangePassword\HandlerTrait;

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
        $input = $_request->getParams();

        $user = Auth::user();
        $user->password = password_hash($input['new_password'], PASSWORD_DEFAULT);

        return $user->save() ?
                $this->changePasswordSuccess() :
                $this->changePasswordError();
    }
}
