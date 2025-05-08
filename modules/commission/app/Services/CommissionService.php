<?php

namespace Modules\commission\app\Services;

use Modules\commission\app\Interfaces\CommissionRuleRepositoryInterface;

class CommissionService
{
    private CommissionRuleRepositoryInterface $rules;

    public function __construct(
        CommissionRuleRepositoryInterface $rules
    ) {
        $this->rules = $rules;
    }
    public function estimate(float $quantity, float $price): float
    {
        $rule = $this->rules->findByQuantity($quantity);

        $fee = ($quantity * $price) * ($rule->percentage / 100);

        return (float) max(
            $rule->minimum_fee,
            min($fee, $rule->maximum_fee)
        );
    }

    public function calculate(string $type, float $quantity, float $price): float
    {
        return $this->estimate($quantity, $price);
    }
}
