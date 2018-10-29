<?php

namespace SkeletonAuth;

trait ForgotPasswordTrait
{
    public function getForgotPassword($response)
    {
        return $this->view->render($response, "auth/forgot-password.twig");
    }

    public function postForgotPassword()
    {
        dump_die("hello world");
    }
}
