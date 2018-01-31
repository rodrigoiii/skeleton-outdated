<?php

namespace Framework\Auth\Middlewares;

use Framework\Auth\Bridge;

trait ValidToLoginTrait
{
    public function renderTemplate($response)
    {
        echo "Login is lock because your login attempts exceed in system limit.";
        die;

        // you can render twig view here
    }

    public function __invoke($request, $response, $next)
    {
        $AuthAttempt = Bridge::model("AuthAttempt");

        if ($AuthAttempt::isAttemptOver())
        {
            if (!$AuthAttempt::isValidToLogin())
            {
                return $this->renderTemplate($response);
            }

            $AuthAttempt::reset();
        }

        return $next($request, $response);
    }
}