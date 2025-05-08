<?php

namespace Modules\commission\app\Interfaces;

use Modules\commission\app\Models\CommissionRule;

interface CommissionRuleRepositoryInterface
{
    public function findByQuantity(float $quantity): CommissionRule;
}
