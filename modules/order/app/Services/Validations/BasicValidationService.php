<?php

namespace Modules\order\app\Services\Validations;

use Illuminate\Validation\ValidationException;

class BasicValidationService
{
    private mixed $limits;
    private mixed $tickSize;
    public function __construct(

    ) {
        $this->limits = config('order.limits');
        $this->tickSize = config('order.tick_size');
    }
    public function validateBasic(float $quantity, int $price): void
    {
        $this->checkQuantity($quantity);
        $this->checkPrice($price);
        $this->checkTickSize($price);
        $this->checkTotalOrderPrice($price, $quantity);
    }

    private function checkQuantity(mixed $quantity): void
    {
        if ($quantity <= 0 || $quantity < $this->limits['min_quantity'] || $quantity > $this->limits['max_quantity']) {
            throw ValidationException::withMessages(['quantity' => __('order.errors.quantity')]);
        }
    }

    private function checkPrice(mixed $price): void
    {
        if ($price <= 0 || $price > $this->limits['max_price']) {
            throw ValidationException::withMessages(['price' => __('order.errors.price')]);
        }
    }

    public function checkTickSize(mixed $price): void
    {
        if (bcmod((string)$price, (string)$this->tickSize) !== '0') {
            throw ValidationException::withMessages(['price' => __('order.errors.price')]);
        }
    }

    public function checkTotalOrderPrice(mixed $price, mixed $quantity): void
    {
        $value = bcmul((string)$price, (string)$quantity);
        if (bccomp($value, (string)$this->limits['max_value']) === 1) {
            throw ValidationException::withMessages(['value' => __('order.errors.value')]);
        }
    }

}
