<?php

namespace SkeletonAuth;

use App\Requests\ChangePasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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

        if (password_verify($input['current_password'], $user->password))
        {
            die("success");
        }

        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }
}
