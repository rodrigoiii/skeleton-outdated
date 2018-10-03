<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SkeletonCore\BaseController;

class WelcomeController extends BaseController
{
    /**
     * Render welcome page.
     *
     * @param  Response $response
     * @return Response
     */
    public function index(Response $response)
    {
        return $this->view->render($response, "index.twig");
    }
}
