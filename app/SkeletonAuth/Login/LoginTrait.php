<?php

namespace SkeletonAuth\Login;

use App\Auth\Auth;
use App\Requests\LoginRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Login\HandlerTrait;

trait LoginTrait
{
    use HandlerTrait;

    public function getLogin(Response $response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    public function postLogin(LoginRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();

        if ($user = Auth::validateCredential($inputs['email'], $inputs['password']))
        {
            // login the user
            Auth::loggedInByUserId($user->getId());

            return $this->loginSuccess($response);
        }

        return $this->loginError($response);
    }

    public function logout(Request $request)
    {
        Auth::loggedOut();
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
