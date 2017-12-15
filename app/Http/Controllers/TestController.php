<?php

namespace App\Http\Controllers;

use Controllers\Controller;
use App\Models\User;
use App\Http\Requests;

class TestController extends Controller
{
	public function index()
	{
		return "Hello World inside of controller";
	}

	public function testView($request, $response)
	{
		return $this->container->twigView->render($response, "index.twig");
	}

	public function testModel()
	{
		$users = User::all();

		echo "<pre>";
		var_dump($users);
	}

	public function testMiddleware($request, $response)
	{
		echo "Print me after calling the Test middleware";
	}
}