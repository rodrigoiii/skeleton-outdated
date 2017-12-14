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
		return $this->phpView->render($response, "index.php");
	}

	public function testModel()
	{
		$users = User::all();

		echo "<pre>";
		var_dump($users);
	}

	public function testMiddleware($request, $response)
	{
		return "Print me after calling the middleware";
	}

	public function testUtilities($request, $response)
	{
		App\Utilities\Test::a();
	}

	public function testPostRequest($request, $response)
	{
		(new Requests\Test($request))->checkRequest();
	}
}