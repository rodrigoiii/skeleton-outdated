<?php

namespace App\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation Library
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => v::notEmpty(),
            'new_password' => v::notEmpty()->passwordStrength(),
            'confirm_new_password' => v::notEmpty()->passwordMatch($this->request->getParam('new_password'))
        ];
    }
}
