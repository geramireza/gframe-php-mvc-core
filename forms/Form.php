<?php

namespace App\core\forms;

use App\Models\Model;

class Form
{
    public static function begin($action,$method)
    {
        echo "<form action='$action' method='$method'>";
        return new Form();
    }

    public static function end()
    {
        echo "</form>";
        return new static();
    }

    public function input(Model $model,string $attribute)
    {
        return new Input($model,$attribute);
    }

    public function textarea(Model $model,string $attribute)
    {
        return new Textarea($model,$attribute);
    }
    public function button(string $type, string $name)
    {
        return new Button($type,$name);
    }
}