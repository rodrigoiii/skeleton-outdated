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
        $inputs = $this->request->getParams();

        return [
            'picture' => v::optional2(v::uploaded()->file()->image()->size(null, "5mb")),
            'first_name' => v::notEmpty()->not(v::numeric()),
            'last_name' => v::notEmpty()->not(v::numeric()),
            'email' => v::notEmpty()->email()->not(v::emailExist($user->email))->not(v::adminEmailExist($user->email)),
            'current_password' => $this->isPasswordModify() ? v::passwordVerify($user->password) : v::alwaysValid(),
            'new_password' => $this->isPasswordModify() ? v::passwordStrength() : v::alwaysValid(),
            'confirm_new_password' => $this->isPasswordModify() ? v::passwordMatch($inputs['new_password']) : v::alwaysValid(),
        ];
    }

    private function isPasswordModify()
    {
        $inputs = $this->request->getParams();

        return !empty($inputs['current_password']) ||
                !empty($inputs['new_password']) ||
                !empty($inputs['confirm_new_password']);
    }
}
