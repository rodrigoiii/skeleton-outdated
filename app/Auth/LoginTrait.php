<?php

namespace SkeletonAuth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Auth;

trait LoginTrait
{
    public function getLogin(Response $response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    public function postLogin(Request $request, Response $response)
    {
        $input = $request->getParams();

        if (Auth::check($input['email'], $input['password']))
        {
            die('success');
        }

        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
