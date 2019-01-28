<?php

namespace App\SkeletonAuth\Requests;

use Respect\Validation\Validator as v;
use SkeletonCore\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Registration rules
     *
     * @return array
     */
    public function rules()
    {
        $inputs = $this->request->getParams();

        return [
            'picture' => v::optional2(v::uploaded()->file()->image()->size(null, "5mb")),
            'first_name' => v::notEmpty()->not(v::numeric()),
            'last_name' => v::notEmpty()->not(v::numeric()),
            'email' => v::notEmpty()->email()->not(v::emailExist())->not(v::adminEmailExist()),
            'password' => v::notEmpty()->passwordStrength(),
            'confirm_password' => v::notEmpty()->passwordMatch($inputs['password'])
        ];
    }

    public function messages()
    {
        return [
            'uploaded' => "Picture must not be empty"
        ];
    }
}
