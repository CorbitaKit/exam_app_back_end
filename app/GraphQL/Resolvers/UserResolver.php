<?php

namespace App\GraphQL\Resolvers;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class UserResolver extends Resolver
{
    protected $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;

        parent::__construct($userService);
    }

    public function resolveCreate($root, $args, $context, $info): mixed
    {
        $validator = Validator::make($args, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|email|unique:users'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ['errors' => $errors, '__typename' => 'UserErrors'];
        }

        return parent::resolveCreate($root, $args, $context, $info);
    }


    public function resolveLogin($root, $args, $context, $info): array
    {
        return $this->service->doLogin($args);
    }

    public function resolveLogout($root, $args, $context, $info): array
    {
        return $this->service->doLogout($args['email']);
    }
}
