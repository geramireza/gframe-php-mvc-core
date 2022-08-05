<?php

namespace App\core;

use App\Controllers\Controller;
use App\Models\Model;
use App\Models\User;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public static Application $app;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public Database $database;
    public Migrations $migraions;
    public Session $session;
    public View $view;
    public string $userClass;
    public ?Model $user = null;

    public function __construct(string $rootDir, array $config)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();
        $this->session = new Session();
        $this->view = new View();
        $this->database = new Database($config['db']);
        $this->migraions = new Migrations($config['db']);
        $this->router = new Router($this->request, $this->response);
        $primaryValue = $this->session->get('user');
        $primaryKey = $this->userClass::primaryKey();
        if ($primaryValue) {
            $this->user = $this->userClass::first([$primaryKey => $primaryValue]);
        }
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        }catch (\Exception $exception){
            $this->response->setStatusCode($exception->getCode());
            echo $this->controller->view('_error',['exception' => $exception]);
        }
    }

    public function login(Model $user)
    {
//        $this->user = $user;
        $primaryKey = $user::primaryKey();
        $primaryValue = $user->$primaryKey;
        $this->session->set('user', $primaryValue);
    }

    public function logout()
    {
        $this->session->forget('user');
    }

    public static function isGuest():bool
    {
        return !self::$app->user;
    }

}