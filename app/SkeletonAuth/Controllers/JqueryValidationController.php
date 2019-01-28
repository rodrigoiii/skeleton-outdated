<?php

namespace App\SkeletonAuth\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use SkeletonCore\BaseController;

class JqueryValidationController extends BaseController
{
    public function emailExist(Request $request)
    {
        $params = $request->getParams();
        $invert = isset($params['invert']);
        $email_exception = isset($params['except']) ? $params['except'] : null;

        if (isset($params['email']))
        {
            $result = !$invert ?
                        v::emailExist($email_exception)->validate($params['email']) :
                        v::not(v::emailExist($email_exception))->validate($params['email']);

            return $result ? "true" : "false";
        }

        \Log::error("Error: Email must be define on '/jv/email-exist' api.");
        return "Parameter is missing!";
    }
}
