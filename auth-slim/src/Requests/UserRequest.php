<?php

namespace AuthSlim\Requests;

use AuthSlim\Validation\Validator;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;

class UserRequest
{
    protected $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function rules()
    {
        switch(strtoupper($this->request->getMethod()))
        {
            case 'POST':
                $rules = [
                    'first_name' => v::notEmpty(),
                    'last_name' => v::notEmpty(),
                    'email' => v::notEmpty()->email()->not(v::emailExist()),
                    'password' => v::notEmpty()->passwordStrength()
                ];
                break;

            case 'PUT':
                $rules = [
                    //
                ];
                break;

            default:
            $rules = [];
        }

        return $rules;
    }

    public function isValid()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}