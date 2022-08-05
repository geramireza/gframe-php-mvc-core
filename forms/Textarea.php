<?php

namespace App\core\forms;
class Textarea extends BaseField
{
    public function renderField(): string
    {
        return sprintf('<textarea rows="7" name="%s" class="form-control %s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? 'is-invalid' : '',
            $this->model->{$this->attribute},
        );
    }
}