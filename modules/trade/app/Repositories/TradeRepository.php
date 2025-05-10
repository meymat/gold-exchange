<?php

namespace Modules\trade\app\Repositories;

use Modules\trade\app\Interfaces\TradeRepositoryInterface;
use Modules\trade\app\Models\Trade;

class TradeRepository implements TradeRepositoryInterface
{
    public function create(array $data): Trade
    {
        return Trade::query()->create($data);
    }
}
