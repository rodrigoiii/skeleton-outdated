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

    public function postRegister(RegisterRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();
        $files = $_request->getUploadedFiles();

        $result = User::create([
            'picture' => upload($files['picture']),
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'email' => $inputs['email'],
            'password' => password_hash($inputs['password'], PASSWORD_DEFAULT)
        ]);

        flash($result instanceof User,
            ['success' => "Success registered!"],
            ['danger' => "Registration not working properly this time. Please try again later."]
        );

        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
