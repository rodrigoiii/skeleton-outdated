<?php

namespace App\Http\Controllers;

use Controllers\Controller;
use App\Models\User;
use App\Http\Requests;
use Session;
use Log;

class TestController extends Controller
{
	public function index()
	{
		return "Hello World inside of controller";
	}

	public function testView($request, $response)
	{
		return $this->twigView->render($response, "index.twig");
	}

	public function testModel()
	{
		$users = User::all();
		_dd($users);
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

	public function testLog()
	{
		// method 1
		Log::write('debug', "test log method 1");

		// method 2
		$this->logger->debug("test log method 2");

		return "Check the file storage/logs/app.log to see the logs";
	}

	public function testAjax($request, $response)
	{
		return $this->twigView->render($response, "test-ajax.twig");
	}
	public function testGetAjaxToBeCall($request, $response)
	{
		$input = $request->getParams();

		return $response->withJson([
			'message' => "Hello World",
			'name' => $input['name']
		]);
	}
	public function testPostAjaxToBeCall($request, $response)
	{
		$input = $request->getParams();

		return $response->withJson([
			'message' => "Hello World",
			'name' => $input['name']
		]);
	}

	public function getTestFlash($request, $response)
	{
		return $this->twigView->render($response, "test-flash.twig");
	}
	public function postTestFlash($request, $response)
	{
		$this->flash->addMessage('message', 'Hello WOrld');
		return $response->withRedirect($this->router->pathFor('test-flash'));
	}
}