<?php

namespace Modules\user\app\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\user\app\Repositories\UserRepository;

class AuthService
{
    private UserRepository $users;

    public function __construct(UserRepository $users) {
        $this->users=$users;
    }

    public function register(array $data): array
    {
        $user = $this->users->create($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function logout($user): void
    {
        $user->tokens()->delete();
    }
}
