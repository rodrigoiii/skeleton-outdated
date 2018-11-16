<?php

namespace SkeletonAuth;

use App\Models\User;
use App\Requests\ForgotPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait ForgotPasswordTrait
{
    public function getForgotPassword(Response $response)
    {
        return $this->view->render($response, "auth/forgot-password.twig");
    }

    public function postForgotPassword(ForgotPasswordRequest $_request, Response $response)
    {
        $input = $_request->getParams();

        $user = User::findByEmail($input['email']);

        if (!is_null($user))
        {
            die('success');
        }

        return $response->withRedirect($this->router->pathFor('auth.forgot-password'));
    }
}
