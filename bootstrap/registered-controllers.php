<?php

# AuthController
$container['AuthController'] = function ($c)
{
	return new App\Http\Controllers\AuthController($c);
};

# Admin\AuthController
$container['Admin\AuthController'] = function ($c)
{
	return new App\Http\Controllers\Admin\AuthController($c);
};


# Admin\SuperAdmin\PageController
$container['Admin\SuperAdmin\PageController'] = function ($c)
{
	return new App\Http\Controllers\Admin\SuperAdmin\PageController($c);
};


# Admin\PageController
$container['Admin\PageController'] = function ($c)
{
	return new App\Http\Controllers\Admin\PageController($c);
};


# Admin\SuperAdmin\ThemeController
$container['Admin\SuperAdmin\ThemeController'] = function ($c)
{
	return new App\Http\Controllers\Admin\SuperAdmin\ThemeController($c);
};


# Admin\SuperAdmin\UserController
$container['Admin\SuperAdmin\UserController'] = function ($c)
{
	return new App\Http\Controllers\Admin\SuperAdmin\UserController($c);
};


# Api\UserController
$container['Api\UserController'] = function ($c)
{
	return new App\Http\Controllers\Api\UserController();
};


# PageController
$container['PageController'] = function ($c)
{
	return new App\Http\Controllers\PageController($c);
};


# Admin\SuperAdmin\ApiUserController
$container['Admin\SuperAdmin\ApiUserController'] = function ($c)
{
	return new App\Http\Controllers\Admin\SuperAdmin\ApiUserController($c);
};

