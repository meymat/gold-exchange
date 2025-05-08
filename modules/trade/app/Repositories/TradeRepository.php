<?php

namespace Modules\trade\app\Repositories;

use Modules\order\app\Models\Order;
use Modules\trade\app\Interfaces\TradeRepositoryInterface;

class TradeRepository implements TradeRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }
}
