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
        return [
            'first_name' => v::notEmpty()->not(v::numeric()),
            'last_name' => v::notEmpty()->not(v::numeric()),
            'email' => v::notEmpty()->email(),
            'password' => v::notEmpty()->passwordStrength(),
            'confirm_password' => v::notEmpty()->equals($this->request->getParam('password'))
        ];
    }
}
