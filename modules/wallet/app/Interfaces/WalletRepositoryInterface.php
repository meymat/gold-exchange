<?php

namespace Modules\wallet\app\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Modules\wallet\app\Models\Wallet;

interface WalletRepositoryInterface
{
    public function forUser(int $userId): Wallet;

    public function sumReservedAmount(int $walletId): float;

    public function sumReservedGold(int $walletId): float;

    public function reserveAmount(int $walletId, float $amount): void;

    public function reserveGold(int $walletId, float $quantity): void;

    public function finalizeWallet(int $buyerWalletId, int $sellerWalletId, int $qty, float $price, float $feeBuyer, float $feeSeller): void;

    public function releaseReservation(int $walletId, string $orderType, float $remainingQty, float $price, float $estimatedFee = 0): void;

    public function addWallet(int $userId): Model;

    public function increaseAmount(int $walletId, int $price): Model;

    public function increaseGold(int $walletId, int $gold): Model;
}
