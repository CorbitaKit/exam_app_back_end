<?php

namespace App\GraphQL\Resolvers;

use App\GraphQL\Validations\TaskValidation;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class TaskResolver extends Resolver
{
    protected $service;

    public function __construct(TaskService $taskService)
    {
        $this->service = $taskService;
        parent::__construct($taskService);
    }

    /**
     * @return array|Model
     */
    public function resolveCreate($root, $args, $context, $info): mixed
    {
        $validator = Validator::make($args, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:To Do,Done',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ['errors' => $errors, '__typename' => 'TaskErrors'];
        }


        return parent::resolveCreate($root, $args, $context, $info);
    }
}
