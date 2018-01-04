<?php

namespace Requests;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    protected $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
}