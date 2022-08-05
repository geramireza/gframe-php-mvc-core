<?php

namespace App\core\forms;

class Button
{
    public string $name;
    public string $type = 'button';
    public function __construct(string $type,string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function __toString()
    {
        return "<button type='$this->type' class='btn btn-primary'>$this->name</button>";
    }
}