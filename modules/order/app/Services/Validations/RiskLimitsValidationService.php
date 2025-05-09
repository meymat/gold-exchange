<?php

namespace Modules\order\app\Services\Validations;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Modules\order\app\Interfaces\OrderRepositoryInterface;

class RiskLimitsValidationService
{
    private OrderRepositoryInterface $orderRepository;
    private mixed $positionLimit;

    public function __construct(
        OrderRepositoryInterface  $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->positionLimit = config('order.risk.position_limit');
    }
    public function validateRiskLimits(int $userId, float $quantity, string $type): void
    {
        $this->checkPositionLimit($userId, $type, $quantity);
        $this->checkRateLimiter($userId);
        $this->checkSelfTrade($userId, $type);
    }

    public function checkPositionLimit(int $userId, string $type, float $quantity): void
    {
        if ($this->orderRepository->sumOpenQuantity($userId, $type) + $quantity > $this->positionLimit) {
            throw ValidationException::withMessages(['quantity' => __('order.errors.position')]);
        }
    }

    public function checkRateLimiter(int $userId): void
    {
        $key = "order-rate:{$userId}";
        if (!RateLimiter::attempt($key, 10, fn() => true)) {
            throw ValidationException::withMessages(['rate' => __('order.errors.rate')]);
        }
    }

    public function checkSelfTrade(int $userId, string $type): void
    {
        if ($this->orderRepository->hasSelfTrade($userId, $type)) {
            throw ValidationException::withMessages(['trade_type' => __('order.errors.self_trade')]);
        }
    }
}
