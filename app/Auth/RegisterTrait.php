<?php

namespace SkeletonAuth;

use App\Models\User;
use App\Requests\RegisterRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RegisterTrait
{
    public function getRegister(Response $response)
    {
        return $this->view->render($response, "auth/register.twig");
    }

    public function postRegister(RegisterRequest $_request)
    {
        $inputs = $_request->getParams();

        $result = User::create([
            'picture' => upload($inputs['picture']),
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'email' => $inputs['email'],
            'password' => $inputs['password']
        ]);

        !d($result); die;
    }
}
