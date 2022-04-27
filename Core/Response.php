<?php

namespace App\Core;

use http\Header;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function send($code, $data)
    {

        $this->setStatusCode($code);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }
}