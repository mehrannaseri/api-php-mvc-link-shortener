<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Middlewares\AuthMiddleware;

class TestController extends BaseController
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['index']));
    }
    public function index()
    {

    }

    public function test()
    {

    }
}