<?php

namespace App\GraphQL\Resolvers;

use App\Interfaces\BaseResolverInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Resolver implements BaseResolverInterface
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function resolveAll(): Collection
    {
        return $this->service->doGet();
    }

    public function resolveFindById($root, $args, $context, $info): Model
    {
        return $this->service->doFindById($args['id']);
    }


    public function resolveCreate($root, $args, $context, $info): mixed
    {
        return $this->service->doCreate($args);
    }


    public function resolveUpdate($root, $args, $context, $info): Model
    {
        return $this->service->doUpdate($args['id'], collect($args)->except('id')->toArray());
    }

    public function resolveDelete($root, $args, $context, $info): bool
    {
        return $this->service->doDelete($args['id']);
    }
}
