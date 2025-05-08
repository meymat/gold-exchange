<?php

namespace Modules\order\app\Interfaces;

use Modules\order\app\Models\Order;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;
    public function sumOpenQuantity(int $userId, string $type): float;
    public function hasSelfTrade(int $userId, string $type): bool;
}
