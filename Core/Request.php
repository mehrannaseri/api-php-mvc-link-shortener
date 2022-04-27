<?php

namespace App\Core;

class Request
{
    public $body = array();

    public function __construct()
    {
        $this->body = (object) $this->getBody();

    }

    public function path()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if($position === false){
            return $path;
        }
        return substr($path, 0, $position);

    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getHeader($header)
    {
        return $_SERVER[$header];
    }

    private function getBody()
    {
        $body = [];

        if($this->method() === 'get'){
            foreach ($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if($this->method() === 'post'){
            foreach ($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}