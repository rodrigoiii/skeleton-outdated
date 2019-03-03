<?php

namespace SkeletonAuthApp\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use SkeletonAuth\Traits\AccountSetting\AccountSetting as AccountSettingTrait;
use SkeletonCore\BaseController;

class AccountSettingController extends BaseController
{
    use AccountSettingTrait;

    /**
     * Success change password handler
     *
     * @param  Response $response
     * @return Response
     */
    public function updateAccountSettingSuccess(Response $response)
    {
        $this->flash->addMessage('success', "Your account was successfully updated!");
        return $response->withRedirect($this->router->pathFor('sklt-chat'));
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
        return $response->withRedirect($this->router->pathFor('sklt-chat'));
    }

    /**
     * No changes info redirect handler
     * @param  Response $response
     * @return Response
     */
    public function noChangesRedirect(Response $response)
    {
        return $response->withRedirect($this->router->pathFor('sklt-chat'));
    }
}
