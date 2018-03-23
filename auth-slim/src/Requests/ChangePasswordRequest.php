<?php

namespace AuthSlim\Requests;

use AuthSlim\Validation\Validator;
use Respect\Validation\Validator as v;

class ChangePasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'current_password' => v::notEmpty()->currentPassword(),
            'new_password' => v::notEmpty()->passwordStrength()->confirmPassword($this->request->getParam('confirm_new_password'))
        ];
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}