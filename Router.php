<?php

namespace App\core;

use App\core\exceptions\NotFoundException;

class Router
{
    public array $routes;
    public Request $request;
    public Response $response;
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path,$callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post($path,$callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * @throws NotFoundException
     */
    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if(!$callback){
            throw new NotFoundException();
        }
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){

            $controller = new $callback[0]();
            $controller->action = $callback[1];
            Application::$app->controller = $controller;
            $callback[0] =  Application::$app->controller;
            foreach (Application::$app->controller->middlewares as $middleware){
                $middleware->execute();
            }
        }
        return call_user_func($callback,$this->request,$this->response);
    }

    public function renderView(string $callback,$params = [])
    {
       return Application::$app->view->renderView($callback,$params);
    }

}