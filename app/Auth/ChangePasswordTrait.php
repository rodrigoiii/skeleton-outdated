<?php

namespace SkeletonAuth;

use App\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Auth;

trait ChangePasswordTrait
{
    public function getChangePassword(Response $response)
    {
        return $this->view->render($response, "auth/change-password.twig");
    }

    public function postChangePassword(ChangePasswordRequest $_request, Response $response)
    {
        $input = $_request->getParams();

        $user = Auth::user();
        $user->password = password_hash($input['new_password'], PASSWORD_DEFAULT);

        flash($user->save(),
            ['success' => "Your password was successfully changed!"],
            ['danger' => "Cannot change the password this time."]
        );

        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }
}
