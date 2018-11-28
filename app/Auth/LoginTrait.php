<?php

namespace SkeletonAuth;

use App\Requests\LoginRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\Auth;

trait LoginTrait
{
    public function getLogin(Response $response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    public function postLogin(LoginRequest $_request, Response $response)
    {
        $inputs = $_request->getParams();

        if (Auth::validateCredential($inputs['email'], $inputs['password']))
        {
            $user = User::findByEmail($inputs['email']);
            Auth::loggedInByUserId($user->getId());

            // Todo: redirect to authenticated homepage
        }

        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    public function logout(Request $request)
    {
        $params = $request->getParams();

        // check if user_id and logged_in_token define
        if (isset($params['user_id'], $params['logged_in_token']))
        {
            $user = User::find($params['user_id']);

            if (!is_null($user))
            {
                if ($user->logged_in_token === $params['logged_in_token'])
                {
                    Auth::loggedOut();
                    return $response->withRedirect($this->router->pathFor('auth.login'));
                }
            }
        }

        return $response->withJson([
            'success' => false,
            'message' => "Invalid user id and token!"
        ]);
    }
}
