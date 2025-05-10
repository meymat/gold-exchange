<?php

namespace Modules\trade\app\Interfaces;

use Modules\trade\app\Models\Trade;

interface TradeRepositoryInterface
{
    public function create(array $data): Trade;
}
