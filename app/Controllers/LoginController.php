<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\LoginTrait;
use SkeletonCore\BaseController;

class LoginController extends BaseController
{
    use LoginTrait;
}
