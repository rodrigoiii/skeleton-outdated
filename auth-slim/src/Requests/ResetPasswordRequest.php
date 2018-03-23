<?php

namespace AuthSlim\Requests;

use AuthSlim\Validation\Validator;
use Respect\Validation\Validator as v;

class ResetPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'password' => v::notEmpty()->passwordStrength()->confirmPassword($this->request->getParam('confirm_password'))
        ];
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}