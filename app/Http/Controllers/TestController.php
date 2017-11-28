<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
	public function index()
	{
		return "Hello World inside of controller";
	}

	public function testView($request, $response)
	{
		return $this->phpView->render($response, "index.php");
	}
}