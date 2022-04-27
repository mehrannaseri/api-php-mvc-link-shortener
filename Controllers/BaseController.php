<?php

namespace App\Controllers;

use App\Core\Middlewares\BaseMiddleware;
use App\Core\Response;

class BaseController
{
    public array $middlewares = [];
    public string $action = '';
    public function response($code, $message, $data = [])
    {
        $response = new Response();
        $data['message'] = $message;
        return $response->send($code, $data);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @param array $middlewares
     */
    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }
}