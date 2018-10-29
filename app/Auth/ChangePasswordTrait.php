<?php

namespace SkeletonAuth;

trait ChangePasswordTrait
{
    public function getChangePassword($response)
    {
        return $this->view->render($response, "auth/change-password.twig");
    }

    public function postChangePassword()
    {
        dump_die("hello world");
    }
}
