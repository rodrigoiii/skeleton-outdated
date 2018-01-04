<?php

namespace App\Http\Requests;

use App\Validation\Validator;
use Respect\Validation\Validator as v;

class Test1 extends BaseRequest
{
    private $request;

    public function __construct($request = [])
    {
        $this->request = $request;
    }

    public function rules()
    {
        return [
            // field => validator
        ];
    }

    public function test()
    {
        $validate = Validator::validate($this->request, $this->rules());
        return $validate::passed();
    }
}