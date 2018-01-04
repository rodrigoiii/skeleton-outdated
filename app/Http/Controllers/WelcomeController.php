<?php

namespace App\Http\Controllers;

use Controllers\Controller;

class WelcomeController extends Controller
{
	public function index($request, $response)
	{
		return $this->twigView->render($response, "index.twig");
	}
}
