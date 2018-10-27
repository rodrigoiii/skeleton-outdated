<?php

namespace SkeletonAuth;

trait LoginTrait
{
    public function getLogin($response)
    {
        return $this->view->render($response, "auth/login.twig");
    }

    public function postLogin()
    {
        dump_die("hello world");
    }
}
