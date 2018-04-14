<?php

namespace AuthSlim\Requests;

use AuthSlim\Auth\Auth;
use AuthSlim\Validation\Validator;
use Respect\Validation\Validator as v;

class AccountDetailRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'first_name' => v::notEmpty(),
            'last_name' => v::notEmpty(),
            'email' => v::notEmpty()->email()->not(v::emailExist(Auth::user()->id)),
            'current_password' => $this->isPasswordDirty() ? v::currentPassword() : v::alwaysValid(),
            'new_password' => $this->isPasswordDirty() ?
                                v::passwordStrength()->confirmPassword($this->request->getParam('confirm_new_password')) :
                                v::alwaysValid()
        ];
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }

    private function isPasswordDirty()
    {
        $input = $this->request->getParams();

        return !empty($input['current_password']) ||
                !empty($input['new_password']) ||
                !empty($input['confirm_new_password']);
    }
}