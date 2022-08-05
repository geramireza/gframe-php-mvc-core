<?php

namespace App\core;

class Request
{
    public function path()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path,'?');
        if(!$position)
            return $path;
        return substr($path,0,$position);
    }
    public function method():string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet():bool
    {
        return $this->method() === 'get';
    }
    public function isPost():bool
    {
        return $this->method() === 'post';
    }

    public function getBody():array
    {
        $body = [];
        if($this->isGet()){
            foreach ($_GET as $key=>$value) {
                $body[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        else{
            foreach ($_POST as $key=>$value) {
                $body[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}