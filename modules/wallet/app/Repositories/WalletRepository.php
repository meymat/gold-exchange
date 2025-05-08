<?php

namespace Modules\wallet\app\Repositories;

use Modules\wallet\app\Interfaces\WalletRepositoryInterface;
use Modules\wallet\app\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{
    public function forUser(int $userId): Wallet
    {
        return Wallet::query()
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function sumReservedAmount(int $walletId): float
    {
        return (float) Wallet::query()
            ->findOrFail($walletId)
            ->reserved_amount;
    }

    public function sumReservedGold(int $walletId): float
    {
        return (float) Wallet::query()
            ->findOrFail($walletId)
            ->reserved_gold;
    }

    public function reserveAmount(int $walletId, float $amount): void
    {
        Wallet::query()
            ->where('id', $walletId)
            ->increment('reserved_amount', $amount);
    }

    public function reserveGold(int $walletId, float $quantity): void
    {
        Wallet::query()
            ->where('id', $walletId)
            ->increment('reserved_gold', $quantity);
    }
}
