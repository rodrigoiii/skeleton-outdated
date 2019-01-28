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
        $inputs = $_request->getParams();
        $files = $_request->getUploadedFiles();

        $user = Auth::user();
        if ($files['picture']->getSize() > 0)
        {
            // delete old picture
            if (file_exists($picture_path = public_path(trim($user->picture, "/"))))
            {
                unlink($picture_path);
            }

            $user->picture = upload($files['picture'], config('auth.upload_path'));
        }
        $user->first_name = $inputs['first_name'];
        $user->last_name = $inputs['last_name'];
        $user->email = $inputs['email'];
        if (!empty($inputs['new_password']))
        {
            $user->password = password_hash($inputs['new_password'], PASSWORD_DEFAULT);
        }

        if ($user->isDirty())
        {
            return $user->save() ?
                    $this->updateAccountSettingSuccess($response) :
                    $this->updateAccountSettingError($response);
        }

        return $this->noChangesRedirect($response);
    }
}
