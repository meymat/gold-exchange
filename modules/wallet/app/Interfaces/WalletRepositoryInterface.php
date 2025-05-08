<?php

namespace Modules\wallet\app\Interfaces;

use Modules\wallet\app\Models\Wallet;

interface WalletRepositoryInterface
{
    public function forUser(int $userId): Wallet;

    public function sumReservedAmount(int $walletId): float;

    public function sumReservedGold(int $walletId): float;

    public function reserveAmount(int $walletId, float $amount): void;

    public function reserveGold(int $walletId, float $quantity): void;
}
