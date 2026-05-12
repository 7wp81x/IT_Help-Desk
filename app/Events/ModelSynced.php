<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelSynced
{
    use Dispatchable, SerializesModels;

    public $model;
    public $action;

    public function __construct($model, $action)
    {
        $this->model = $model;
        $this->action = $action;
    }
}