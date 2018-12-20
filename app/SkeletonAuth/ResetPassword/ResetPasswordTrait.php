<?php

namespace SkeletonAuth\ResetPassword;

use App\Requests\ResetPasswordRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SkeletonAuth\ResetPassword\HandlerTrait;

trait ResetPasswordTrait
{
    use HandlerTrait;

    /**
     * Display reset password page
     *
     * @param  Response $response
     * @return Response
     */
    public function getResetPassword()
    {
        return $this->view->render($response, "auth/reset-password.twig");
    }

    /**
     * Post data
     *
     * @param  ResetPasswordRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function postResetPassword(ResetPasswordRequest $_request, Response $response)
    {

    }
}
