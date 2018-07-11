<?php

namespace App\Http\Requests;

use FrameworkCore\Validation\Validator;
use FrameworkCore\BaseRequest;
use Respect\Validation\Validator as v;

class UserRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation
     * @return array
     */
    public function rules()
    {
        switch(strtoupper($this->request->getMethod()))
        {
            case 'POST':
                $rules = [
                    'first_name' => v::notEmpty(),
                    'last_name' => v::notEmpty(),
                    'email' => v::email()
                ];
                break;

            case 'PUT':
                $rules = [
                    'first_name' => v::notEmpty(),
                    'last_name' => v::notEmpty(),
                    'email' => v::email()
                ];
                break;

            default:
            $rules = [];
        }

        return $rules;
    }

    /**
     * Check if input provided passed on rules.
     * @return boolean
     */
    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}
