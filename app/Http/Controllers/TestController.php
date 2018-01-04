<?php

namespace App\Http\Controllers;

use Respect\Validation\Validator as v;

class TestController extends BaseController
{
	public function index($request, $response)
	{
		return $this->twigView->render($response, "test.twig");
	}

    public function index2($request, $response)
    {
        $a = $this->validator->validate($request, [
            'test_field' => v::test()
        ]);

        return $response->withRedirect($this->router->pathFor('test'));
    }
}
