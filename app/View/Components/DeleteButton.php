<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    public $action;
    public $id;
    public $buttonText;

    public function __construct($action, $id, $buttonText = 'Eliminar')
    {
        $this->action = $action;
        $this->id = $id;
        $this->buttonText = $buttonText;
    }

    public function render()
    {
        return view('components.delete-button');
    }
}
