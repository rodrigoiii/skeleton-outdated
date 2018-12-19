<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\ChangePassword\ChangePasswordTrait;
use SkeletonCore\BaseController;

class ChangePasswordController extends BaseController
{
    use ChangePasswordTrait;
}
