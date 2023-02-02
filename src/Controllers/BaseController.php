<?php

namespace Pixled\PasswordGenerator\Controllers;

use Illuminate\Http\Response;

class BaseController
{
    protected $response;
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}