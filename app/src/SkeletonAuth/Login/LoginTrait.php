<?php

namespace SkeletonAuth\Login;

use App\SkeletonAuth\Auth;
use App\SkeletonAuth\Requests\LoginRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

trait LoginTrait
{
    use HandlerTrait;

    /**
     * Display login page
     *
     * @param  Response $response
     * @return Response
     */
    public function getLogin(Response $response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    /**
     * Post user credential
     * @param  LoginRequest $_request
     * @param  Response     $response
     * @return Response
     */
    public function postLogin(LoginRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();

        if ($user = Auth::validateCredential($inputs['email'], $inputs['password']))
        {
            // login the user
            Auth::logInByUserId($user->getId());

            return $this->loginSuccess($response);
        }

        return $this->loginError($response);
    }

    /**
     * Logout user
     *
     * @param  Response $response
     * @return Response
     */
    public function logout(Response $response)
    {
        Auth::logOut();
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }
}
