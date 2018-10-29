<?php

namespace SkeletonAuth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait ForgotPasswordTrait
{
    public function getForgotPassword(Response $response)
    {
        return $this->view->render($response, "auth/forgot-password.twig");
    }

    public function postForgotPassword()
    {
        dump_die("hello world");
    }
}
