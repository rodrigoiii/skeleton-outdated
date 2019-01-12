<?php

namespace App\SkeletonAuthAdmin\Requests;

use App\SkeletonAuthAdmin\Auth;
use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

/**
 * Requirements:
 * - AdminMiddleware was used
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
        $admin = Auth::admin();

        return [
            'current_password' => v::notEmpty()->passwordVerify($admin->password),
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($this->request->getParam('new_password'))
        ];
    }
}
