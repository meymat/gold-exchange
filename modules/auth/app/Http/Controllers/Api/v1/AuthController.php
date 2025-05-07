<?php

namespace Modules\auth\app\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use Modules\auth\app\Http\Requests\LoginRequest;
use Modules\auth\app\Http\Requests\RegisterRequest;
use Modules\core\app\Http\Controllers\CoreController;
use Modules\user\app\Services\AuthService;

class AuthController extends CoreController
{
    public function __construct(private readonly AuthService $auth) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->auth->register($request->validated());
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->login(
            $request->input('email'),
            $request->input('password')
        );
        return response()->json($result);
    }

    public function logout(): JsonResponse
    {
        $this->auth->logout(auth()->user());
        return response()->json(['message' => __('auth.logged_out')]);
    }
}
