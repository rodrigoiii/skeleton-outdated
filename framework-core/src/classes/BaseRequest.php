<?php

namespace FrameworkCore;

use Psr\Http\Message\ServerRequestInterface;

class BaseRequest
{
    protected $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }
}