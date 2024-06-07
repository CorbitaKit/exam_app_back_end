<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository extends Repository
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }
}
