<?php

namespace App\Http\Requests;

use SlimRodrigoCore\BaseRequest;
use Respect\Validation\Validator as v;

class UserRequest extends BaseRequest
{
    /**
     * Create rules using Respect Validation Library
     *
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
}
