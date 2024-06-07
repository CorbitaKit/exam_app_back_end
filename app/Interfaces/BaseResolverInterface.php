<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseResolverInterface
{
    public function resolveAll(): Collection;

    public function resolveFindById($root, $args, $context, $info): Model;

    public function resolveCreate($root, $args, $context, $info): mixed;

    public function resolveUpdate($root, $args, $context, $info): Model;

    public function resolveDelete($root, $args, $context, $info): bool;
}
