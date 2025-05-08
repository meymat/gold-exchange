<?php

namespace Modules\commission\app\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\commission\app\Interfaces\CommissionRuleRepositoryInterface;
use Modules\commission\app\Models\CommissionRule;

class CommissionRuleRepository implements CommissionRuleRepositoryInterface
{
    public function findByQuantity(float $quantity): CommissionRule
    {
        $rule = CommissionRule::query()
            ->where('from_amount', '<=', $quantity)
            ->where('to_amount',   '>=', $quantity)
            ->first();

        if (! $rule) {
            throw new ModelNotFoundException(
                "No commission rule found for quantity {$quantity}"
            );
        }

        return $rule;
    }
}
