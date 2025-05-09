<?php

namespace Modules\order\app\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\commission\app\Services\CommissionService;
use Modules\order\app\Enums\OrderStatuses;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\order\app\Jobs\MatchOrderJob;
use Modules\order\app\Models\Order;
use Modules\order\app\Services\Validations\BasicValidationService;
use Modules\order\app\Services\Validations\RiskLimitsValidationService;
use Modules\order\app\Services\Validations\WalletValidationService;
use Modules\trade\app\Enums\TradeTypes;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;

class OrderService
{
    private BasicValidationService $basicValidationService;
    private RiskLimitsValidationService $riskLimitsValidationService;
    private WalletValidationService $walletValidationService;
    private WalletRepositoryInterface $walletRepository;
    private CommissionService $commissionService;
    private OrderRepositoryInterface $orderRepository;

    public function __construct
    (
        BasicValidationService $basicValidationService,
        RiskLimitsValidationService $riskLimitsValidationService,
        WalletValidationService $walletValidationService,
        WalletRepositoryInterface $walletRepository,
        CommissionService $commissionService,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->basicValidationService = $basicValidationService;
        $this->riskLimitsValidationService = $riskLimitsValidationService;
        $this->walletValidationService = $walletValidationService;
        $this->walletRepository = $walletRepository;
        $this->commissionService = $commissionService;
        $this->orderRepository = $orderRepository;
    }

    public function createBuyOrder(array $data): Order
    {

        $quantity   = $data['quantity'];
        $price   = $data['price'];
        $userId = $data['user_id'];
        $this->basicValidationService->validateBasic($quantity, $price);
        $this->riskLimitsValidationService->validateRiskLimits($userId, $quantity, TradeTypes::Buy->value);
        $wallet = $this->walletRepository->forUser($userId);
        $fee    = $this->commissionService->estimate($data['quantity'], $data['price']);
        $this->walletValidationService->ensureSufficientFunds($wallet, $data['price'], $data['quantity'], $fee);

        $order = $this->orderRepository->create([
            'user_id'           => $data['user_id'],
            'trade_type'              => TradeTypes::Buy->value,
            'price'             => $data['price'],
            'initial_quantity'  => $data['quantity'],
            'remaining_quantity'=> $data['quantity'],
            'order_status'         => OrderStatuses::Open->value,
        ]);

        MatchOrderJob::dispatch($order->id);

        return $order;
    }

    public function createSellOrder(array $data): Order
    {
        $quantity   = $data['quantity'];
        $price   = $data['price'];
        $userId = $data['user_id'];
        $this->basicValidationService->validateBasic($quantity, $price);
        $this->riskLimitsValidationService->validateRiskLimits($userId, $quantity, TradeTypes::Sell->value);
        $wallet = $this->walletRepository->forUser($userId);
        $this->walletValidationService->ensureSufficientGold($wallet, $data['quantity']);

        $order = $this->orderRepository->create([
            'user_id'           => $data['user_id'],
            'trade_type'              => TradeTypes::Sell->value,
            'price'             => $data['price'],
            'initial_quantity'  => $data['quantity'],
            'remaining_quantity'=> $data['quantity'],
            'order_status'         => OrderStatuses::Open->value,
        ]);

        MatchOrderJob::dispatch($order->id);

        return $order;
    }

    public function history(int $userId):Collection
    {
        return $this->orderRepository->history($userId);
    }

    public function cancel(int $orderId, int $userId): void
    {
        $this->orderRepository->cancel($orderId, $userId);
    }
}
