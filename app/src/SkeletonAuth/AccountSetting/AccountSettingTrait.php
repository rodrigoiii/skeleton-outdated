<?php

namespace SkeletonAuth\AccountSetting;

use App\SkeletonAuth\Auth;
use App\SkeletonAuth\Requests\AccountSettingRequest;
use Psr\Http\Message\ResponseInterface as Response;

trait AccountSettingTrait
{
    use HandlerTrait;

    /**
     * Display account setting page
     *
     * @param  Response $response
     * @return Response
     */
    public function getAccountSetting(Response $response)
    {
        return $this->view->render($response, "auth/account-setting.twig");
    }

    /**
     * Save the changes
     *
     * @param  AccountSettingRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function postAccountSetting(AccountSettingRequest $_request, Response $response)
    {
        // $new_password = $_request->getParam('new_password');

        // $user = Auth::user();
        // $user->password = password_hash($new_password, PASSWORD_DEFAULT);

        // return $user->save() ?
        //         $this->changePasswordSuccess($response) :
        //         $this->changePasswordError($response);
    }
}
