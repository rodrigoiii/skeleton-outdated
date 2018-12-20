<?php

namespace App\Requests;

use Respect\Validation\Validator as v;
use SkeletonAuth\Auth;
use SkeletonCore\BaseRequest;

/**
 * Requirements:
 * - UserMiddleware was used
 */
class ChangePasswordRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation Library
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'current_password' => v::notEmpty()->passwordVerify($user->password),
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($this->request->getParam('new_password'))
        ];
    }
}
