<?php

namespace SkeletonAuth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait LoginTrait
{
    public function getLogin(Response $response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    public function postLogin()
    {
        dump_die("hello world");
    }
}
