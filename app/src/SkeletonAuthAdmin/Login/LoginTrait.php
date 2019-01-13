<?php

namespace SkeletonAuthAdmin\Login;

use App\SkeletonAuthAdmin\Auth;
use App\SkeletonAuthAdmin\Requests\LoginRequest;
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
        return $this->view->render($response, "auth-admin/login.twig");
    }

    /**
     * Post admin credential
     * @param  LoginRequest $_request
     * @param  Response     $response
     * @return Response
     */
    public function postLogin(LoginRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();

        if ($admin = Auth::validateCredential($inputs['email'], $inputs['password']))
        {
            // login the admin
            Auth::logInByAdminId($admin->getId());

            return $this->loginSuccess($response);
        }

        return $this->loginError($response);
    }

    /**
     * Logout admin
     *
     * @param  Response $response
     * @return Response
     */
    public function logout(Response $response)
    {
        Auth::logOut();
        return $response->withRedirect($this->router->pathFor('auth-admin.login'));
    }
}
