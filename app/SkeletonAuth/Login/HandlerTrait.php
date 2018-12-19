<?php

namespace SkeletonAuth\Login;

use Psr\Http\Message\ResponseInterface as Response;

trait HandlerTrait
{
    /**
     * Success login handler
     *
     * @param  Response $response
     * @return Response
     */
    public function loginSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Successfully login!");
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    /**
     * Error login handler
     *
     * @param  Response $response
     * @return Response
     */
    public function loginError(Response $response)
    {
        $this->flash->addMessage('error', "Invalid email or password!");
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
