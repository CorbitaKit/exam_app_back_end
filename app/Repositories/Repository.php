<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements BaseRepositoryInterface
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    public function get(): Collection
    {
        return $this->model::get();
    }

    public function find(int $id): Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $updatedData): Model
    {
        $data = $this->model->findOrFail($id);
        $data->update($updatedData);

        return $data;
    }

    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }
}
