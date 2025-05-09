<?php

namespace Modules\order\app\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\order\app\Enums\OrderStatuses;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\order\app\Models\Order;
use Modules\trade\app\Enums\TradeTypes;

class OrderRepository implements OrderRepositoryInterface
{

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
            ->where('type', $type)
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
            ->where('type', $opposite)
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
            ->where('type', $opposite)
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
                'status_id' => $status,
            ]);
    }
}
