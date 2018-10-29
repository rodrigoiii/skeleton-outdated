<?php

namespace SkeletonAuth;

trait RegisterTrait
{
    public function getRegister($response)
    {
        return $this->view->render($response, "auth/register.twig");
    }

    public function postRegister()
    {
        dump_die("hello world");
    }
}
