<?php

namespace App\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation Library
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => v::notEmpty()->stringType(),
            'last_name' => v::notEmpty()->stringType(),
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()->passwordStrength(),
            'confirm_password' => v::notEmpty()->passwordMatch($this->request->getParam('password')),
        ];

        return $rules;
    }
}
