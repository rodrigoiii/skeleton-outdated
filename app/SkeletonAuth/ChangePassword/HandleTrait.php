<?php

namespace SkeletonAuth\ChangePassword;

use Psr\Http\Message\ResponseInterface as Response;

trait HandleTrait
{
    public function changePasswordSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your password was successfully changed!");
        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }

    public function changePasswordError(Response $response)
    {
        $this->flash->addMessage('error', "Change password not working properly. Please try again later!");
        return $response->withRedirect($this->router->pathFor('auth.change-password'));
    }
}
