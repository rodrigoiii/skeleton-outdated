<?php

namespace AuthSlim\Requests;

use AuthSlim\Validation\Validator;
use Respect\Validation\Validator as v;

class ForgotPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email' => v::notEmpty()->email()->emailExist(),
        ];
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}