<?php

namespace SkeletonAuth\ChangePassword;

use App\Auth\Auth;
use App\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\ChangePassword\HandlerTrait;

trait ChangePasswordTrait
{
    use HandlerTrait;

    public function getChangePassword(Response $response)
    {
        return $this->view->render($response, "auth/change-password.twig");
    }

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
