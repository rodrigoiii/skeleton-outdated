<?php

namespace SkeletonAuthApp\Requests;

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
        $inputs = $this->request->getParams();

        return [
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($inputs['new_password'])
        ];
    }
}
