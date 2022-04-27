<?php

namespace App\Core\Middlewares;

use App\Core\Application;
use App\Core\Exceptions\UnAuthorizeException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
            if(in_array(@Application::$app->controller->action, $this->actions) and ! Application::auth()){
                throw new UnAuthorizeException();
            }

    }
}