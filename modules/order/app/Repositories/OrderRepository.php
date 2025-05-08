<?php

namespace Modules\order\app\Repositories;

use Modules\order\app\Enums\OrderStatuses;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\order\app\Models\Order;
use Modules\trade\app\Enums\TradeTypes;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }

    public function sumOpenQuantity(int $userId, string $type): float
    {
        return (float) Order::query()
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
}
