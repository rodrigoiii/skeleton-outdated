<?php

namespace SkeletonAuthApp\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Login rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()
        ];
    }
}
