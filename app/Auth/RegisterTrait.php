<?php

namespace SkeletonAuth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RegisterTrait
{
    public function getRegister(Response $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }

    public function postRegister(Request $request)
    {
        !d($request->getParams());
    }
}
