<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Register\RegisterTrait;
use SkeletonCore\BaseController;

class RegisterController extends BaseController
{
    use RegisterTrait;
}
