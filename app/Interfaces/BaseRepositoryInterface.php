<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function get(): Collection;

    public function find(int $id): Model;

    public function create(array $data): mixed;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;
}
