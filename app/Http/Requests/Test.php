<?php

namespace App\Http\Requests;

use Psr\Http\Message\ServerRequestInterface as Request;

class Test
{
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function rules()
	{
		return [
			'field' => true,
		];
	}

	public function checkRequest($redirect_to = "")
	{
		if (empty($redirect_to))
		{
			$redirect_to = $this->request->getUri()->getPath();
		}

		// $validate = Validator::validate($this->request, $this->rules());
		// if ($validate::failed())
		// {
		// 	header("Location: " . $redirect_to);
		// }
		if (false)
		{
			header("Location: " . $redirect_to);
		}
	}
}