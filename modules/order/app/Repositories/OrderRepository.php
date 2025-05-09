<?php

namespace Modules\order\app\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\order\app\Enums\OrderStatuses;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\order\app\Models\Order;
use Modules\trade\app\Enums\TradeTypes;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;
use Modules\wallet\app\Repositories\WalletRepository;

class OrderRepository implements OrderRepositoryInterface
{
    private WalletRepository $walletRepository;

    public function __construct
    (
        WalletRepositoryInterface $walletRepository
    ){
        $this->walletRepository = $walletRepository;
    }

    public function find(int $id): ?Order
    {
        return Order::query()->find($id);
    }

    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }

    public function decrementQuantity(int $orderId, $matchQty): void
    {
        Order::query()
            ->where('id', $orderId)
            ->decrement('remaining_quantity', $matchQty);
    }

    public function sumOpenQuantity(int $userId, string $type): float
    {
        return (float)Order::query()
            ->where('user_id', $userId)
            ->where('trade_type', $type)
            ->whereIn('order_status', [
                OrderStatuses::Open->value,
                OrderStatuses::PartiallyFilled->value,
            ])->sum('remaining_quantity');
    }

    public function hasSelfTrade(int $userId, string $type): bool
    {
        $opposite = $type === TradeTypes::Buy->value
            ? TradeTypes::Sell->value
            : TradeTypes::Buy->value;

        return Order::query()
            ->where('user_id', $userId)
            ->where('trade_type', $opposite)
            ->whereIn('order_status', [
                OrderStatuses::Open->value,
                OrderStatuses::PartiallyFilled->value,
            ])->exists();
    }

    public function findOpposingOrders(string $type, float $price): Collection
    {
        $opposite = $type === TradeTypes::Buy->value
            ? TradeTypes::Sell->value
            : TradeTypes::Buy->value;

        return Order::query()
            ->where('trade_type', $opposite)
            ->where('price', $opposite === TradeTypes::Sell->value ? '<=' : '>=', $price)
            ->whereIn('order_status', [OrderStatuses::Open->value, OrderStatuses::PartiallyFilled->value])
            ->orderBy('price', $opposite === TradeTypes::Sell->value ? 'desc' : 'asc')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->get();
    }

    public function updateStatus(int $orderId, float $remainingQty): void
    {
        $status = $remainingQty <= 0
            ? OrderStatuses::Filled->value
            : OrderStatuses::PartiallyFilled->value;

        Order::query()->where('id', $orderId)
            ->update([
                'order_status' => $status,
            ]);
    }

    public function history(int $userId): Collection
    {
        return Order::query()
            ->where('user_id', $userId)
            ->whereIn('order_status', [
                OrderStatuses::Filled->value,
                OrderStatuses::Cancelled->value,
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function cancel(int $orderId, int $userId): void
    {
        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->whereIn('order_status', [
                OrderStatuses::Open->value,
                OrderStatuses::PartiallyFilled->value,
            ])
            ->firstOrFail();

        $remainingQty   = $order->remaining_quantity;
        $price          = $order->price;
        $estimatedFee   = 0;

        $order->update([
            'order_status'         => OrderStatuses::Cancelled->value,
            'remaining_quantity'=> 0,
        ]);

        $this->walletRepository->releaseReservation($order->user->wallet->id, $order->trade_type, $remainingQty, $price, $estimatedFee);
    }
}
