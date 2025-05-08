<?php

namespace Modules\order\app\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Modules\order\app\Models\Order;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Order;

    public function create(array $data): Order;

    public function sumOpenQuantity(int $userId, string $type): float;

    public function hasSelfTrade(int $userId, string $type): bool;

    public function findOpposingOrders(string $type, float $price): Collection;

    public function updateStatus(int $orderId, float $remainingQty): void;
}
