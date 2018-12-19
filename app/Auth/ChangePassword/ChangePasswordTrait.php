<?php

namespace SkeletonAuth\ChangePassword;

use App\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\Auth;
use SkeletonAuth\ChangePassword\HandleTrait;

trait ChangePasswordTrait
{
    use HandleTrait;

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
