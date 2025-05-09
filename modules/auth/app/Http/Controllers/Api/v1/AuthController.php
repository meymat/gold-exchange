<?php

namespace Modules\auth\app\Http\Controllers\Api\v1;

use Illuminate\Http\JsonResponse;
use Modules\auth\app\Http\Requests\LoginRequest;
use Modules\auth\app\Http\Requests\RegisterRequest;
use Modules\auth\app\Resources\AuthResource;
use Modules\core\app\Http\Controllers\CoreController;
use Modules\user\app\Services\AuthService;
use Modules\wallet\app\Repositories\WalletRepository;

class AuthController extends CoreController
{
    private AuthService $auth;
    private WalletRepository $walletRepository;

    public function __construct(AuthService $authService,WalletRepository $walletRepository) {
        $this->auth = $authService;
        $this->walletRepository = $walletRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->auth->register($request->validated());
        $user = $result['user'];
        $this->walletRepository->addWallet($user->id);

        return (new AuthResource($result))
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->login(
            $request->input('email'),
            $request->input('password')
        );

        return (new AuthResource($result))
            ->response()
            ->setStatusCode(200);
    }

    public function logout(): JsonResponse
    {
        $this->auth->logout(auth()->user());

        return response()->json(['message' => __('auth.logged_out')]);
    }
}
