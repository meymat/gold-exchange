<?php

namespace Modules\trade\app\Interfaces;

use Modules\order\app\Models\Order;

interface TradeRepositoryInterface
{
    public function create(array $data): Order;
}
