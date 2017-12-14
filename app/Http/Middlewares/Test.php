<?php

namespace App\Http\Middlewares;

use Middlewares\Middleware;

class Test extends Middleware
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function __get($property)
	{
		if (isset($this->container->{$property}))
		{
			return $this->container->{$property};
		}
	}
}
