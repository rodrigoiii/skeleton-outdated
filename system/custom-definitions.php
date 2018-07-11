<?php

use App\Http\Requests\UserRequest;
use Psr\Container\ContainerInterface;

# custom definitions
return [
    UserRequest::class => function(ContainerInterface $c)
    {
        return new UserRequest($c->get('request'));
    }
];
