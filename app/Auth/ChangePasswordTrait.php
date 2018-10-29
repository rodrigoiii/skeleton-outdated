<?php

namespace SkeletonAuth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait ChangePasswordTrait
{
    public function getChangePassword(Response $response)
    {
        return $this->view->render($response, "auth/change-password.twig");
    }

    public function postChangePassword()
    {
        dump_die("hello world");
    }
}
