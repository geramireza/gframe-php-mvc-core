<?php

namespace App\core\middlewares;

use App\core\Application;
use App\core\exceptions\ForbidenException;

class AuthMiddleware extends Middleware
{
    public array $actions = [];
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * @throws ForbidenException
     */
    public function execute()
    {
        if(Application::isGuest()){
            if(empty($this->actions) || in_array(Application::$app->controller->action,$this->actions)){
                throw new ForbidenException();
            }
        }

    }
}