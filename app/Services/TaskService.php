<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService extends Service
{
    protected $repo;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->repo = $taskRepository;

        parent::__construct($taskRepository);
    }
}
