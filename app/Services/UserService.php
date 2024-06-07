<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UserService extends Service
{
    protected $repo;
    protected $taskrepo;

    public function __construct(UserRepository $userRepository, TaskRepository $taskRepository)
    {
        $this->repo = $userRepository;
        $this->taskrepo = $taskRepository;

        parent::__construct($userRepository);
    }

    public function doLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'token' => null,
                'user' => null
            ];
        }

        $user = $this->repo->doFindByEmail($credentials['email']);

        return [
            'token' => $user->createToken("api")->plainTextToken,
            'user' => $user
        ];
    }

    public function doLogout(string $email): array
    {
        $user = $this->repo->doFindByEmail($email);
        $user->tokens()->delete();
        return [
            'status' => 'true',
            'message' => 'User Logged out successfully',
        ];
    }
}
