<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\ForgotPasswordTrait;
use SkeletonCore\BaseController;

class ForgotPasswordController extends BaseController
{
    use ForgotPasswordTrait;
}
