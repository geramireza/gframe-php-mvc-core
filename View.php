<?php

namespace App\core;

class View
{
    public string $title = '';
    public function renderView(string $callback,$params = [])
    {
        $viewContent = $this->viewContent($callback,$params);
        $layoutContent = $this->layoutContent();
        return str_replace("{{content}}",$viewContent,$layoutContent);
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        require_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    protected function viewContent($view,$params)
    {
        foreach ($params as $key=>$value) {
            $$key = $value;
        }
        ob_start();
        require_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

}