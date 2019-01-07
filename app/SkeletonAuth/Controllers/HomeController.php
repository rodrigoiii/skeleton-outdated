<?php

namespace App\SkeletonAuthApp\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonCore\BaseController;

class HomeController extends BaseController
{
    public function index(Response $response)
    {
        return $this->view->render($response, "auth/home.twig");
    }
}
