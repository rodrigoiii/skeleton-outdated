<?php

namespace SkeletonAuthApp\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
{
    /**
     * Forgot password rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => v::notEmpty()->email()->emailExist()
        ];
    }
}
