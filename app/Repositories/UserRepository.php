<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function doFindByEmail(string $email): User
    {
        return $this->model->whereEmail($email)->first();
    }
}
