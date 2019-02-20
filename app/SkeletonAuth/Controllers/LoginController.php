<?php

namespace SkeletonAuthApp\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\Traits\Login\Login as LoginTrait;
use SkeletonCore\BaseController;

class LoginController extends BaseController
{
    use LoginTrait;

    public function loginSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Successfully login!");
        return $response->withRedirect($this->router->pathFor('sklt-chat'));
    }
}
