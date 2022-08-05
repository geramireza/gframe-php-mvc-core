<?php

namespace gframe\phpmvc;

class Response
{
    public function setStatusCode($code)
    {
        http_response_code($code);
    }

    public function redirect($uri)
    {
        header("Location:.$uri");
    }
}