<?php

namespace App\SkeletonAuth\Requests;

use App\SkeletonAuth\Auth;
use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

/**
 * Requirements:
 * - UserMiddleware was used
 */
class AccountSettingRequest extends BaseRequest
{
    /**
     * Change password rules
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'picture' => v::optional2(v::uploaded()->file()->image()->size(null, "5mb")),
            'first_name' => v::notEmpty()->not(v::numeric()),
            'last_name' => v::notEmpty()->not(v::numeric()),
            'email' => v::notEmpty()->email()->not(v::emailExist($user->email))->not(v::adminEmailExist($user->email)),
            'current_password' => v::optional(v::passwordVerify($user->password)),
            'new_password' => v::optional(v::passwordStrength()),
            'confirm_new_password' => v::optional(v::passwordMatch($this->request->getParam('new_password')))
        ];
    }
}
