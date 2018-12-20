<?php

namespace SkeletonAuth\ResetPassword;

use Psr\Http\Message\ResponseInterface as Response;

trait HandlerTrait
{
    /**
     * Success reset password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function resetPasswordSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your password was successfully changed!");
        return $response->withRedirect($this->router->pathFor('auth.reset-password'));
    }

    /**
     * Error reset password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function resetPasswordError(Response $response)
    {
        $this->flash->addMessage('error', "Change password not working properly. Please try again later!");
        return $response->withRedirect($this->router->pathFor('auth.reset-password'));
    }
}
