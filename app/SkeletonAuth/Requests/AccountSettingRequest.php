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
            'picture' => "uploaded|file|image|size:null,5mb",
            'first_name' => v::notEmpty()->not(v::numeric()),
            'last_name' => v::notEmpty()->not(v::numeric()),
            'email' => v::notEmpty()->email()->not(v::emailExist($user->email)),
            'current_password' => v::notEmpty()->passwordVerify($user->password),
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($this->request->getParam('new_password'))
        ];
    }
}
