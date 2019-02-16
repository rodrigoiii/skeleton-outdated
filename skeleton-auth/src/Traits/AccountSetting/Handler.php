<?php

namespace SkeletonAuth\Traits\AccountSetting;

use Psr\Http\Message\ResponseInterface as Response;

trait Handler
{
    /**
     * Success change password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function updateAccountSettingSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your account was successfully updated!");
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    /**
     * Error change password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function updateAccountSettingError(Response $response)
    {
        $this->flash->addMessage('error', "Updating account not working properly. Please try again later!");
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }

    /**
     * No changes info redirect handler
     * @param  Response $response
     * @return Response
     */
    public function noChangesRedirect(Response $response)
    {
        return $response->withRedirect($this->router->pathFor('auth.home'));
    }
}
