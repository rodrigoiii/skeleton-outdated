<?php

namespace App\Http\Controllers;

use Controllers\Controller;
use App\Models\User;
use App\Http\Requests;
use Session;

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

	public function testMiddleware()
	{
		echo "Print me after calling the Test middleware";
	}

	public function testSession()
	{
		// Session::destroy();
		// Session::put(str_random(5), rand(1, 100));

		$a = Session::all();
		_dd($a);
	}
}