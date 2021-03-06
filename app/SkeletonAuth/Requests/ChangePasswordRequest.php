<?php

namespace SkeletonAuthApp\Requests;

use SkeletonAuthApp\Auth;
use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

/**
 * Requirements:
 * - UserMiddleware was used
 */
class ChangePasswordRequest extends BaseRequest
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
            'current_password' => v::notEmpty()->passwordVerify($user->password),
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($inputs['new_password'])
        ];
    }
}
