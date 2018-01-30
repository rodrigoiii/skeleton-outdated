<?php

namespace App\Http\Controllers;

use Framework\BaseController;

class WelcomeController extends BaseController
{
	public function index($request, $response)
	{
		return $this->twigView->render($response, "index.twig");
	}
}
