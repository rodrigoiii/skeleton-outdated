<?php

namespace SkeletonAuth\Traits\ChangePassword;

use Psr\Http\Message\ResponseInterface as Response;

trait Handler
{
    /**
     * Success change password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function changePasswordSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your password was successfully changed!");
        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }

    /**
     * Error change password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function changePasswordError(Response $response)
    {
        $this->flash->addMessage('error', "Change password not working properly. Please try again later!");
        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }
}
