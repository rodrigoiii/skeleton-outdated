<?php

namespace App\Http\Controllers;

class WelcomeController extends BaseController
{
	public function index($request, $response)
	{
		return $this->twigView->render($response, "index.twig");
	}
}
