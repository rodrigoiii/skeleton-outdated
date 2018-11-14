<?php

namespace App\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation Library
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()->passwordStrength()
        ];

        return $rules;
    }
}
