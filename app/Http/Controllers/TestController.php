<?php

namespace App\Http\Controllers;

use App\Models\User;

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
}