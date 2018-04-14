<?php

namespace AuthSlim\Requests;

use AuthSlim\Validation\Validator;
use Respect\Validation\Validator as v;

class RegisterRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'first_name' => v::notEmpty(),
            'last_name' => v::notEmpty(),
            'email' => v::notEmpty()->email()->not(v::emailExist()),
            'password' => v::notEmpty()->passwordStrength()->confirmPassword($this->request->getParam('confirm_password')),
        ];
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}