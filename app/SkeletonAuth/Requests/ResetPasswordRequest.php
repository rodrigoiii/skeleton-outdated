<?php

namespace App\SkeletonAuthApp\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Reset password rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($this->request->getParam('new_password'))
        ];
    }
}
