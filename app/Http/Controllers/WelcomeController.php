<?php

namespace App\Http\Controllers;

use FrameworkCore\BaseController;

class WelcomeController extends BaseController
{
    /**
     * Render welcome page
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function index($request, $response)
    {
        return $this->view->render($response, "index.twig");
    }
}
