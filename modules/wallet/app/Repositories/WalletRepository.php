<?php

namespace Modules\wallet\app\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Modules\trade\app\Enums\TradeTypes;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;
use Modules\wallet\app\Models\Wallet;

class WalletRepository implements WalletRepositoryInterface
{
    private Wallet $model;

    public function __construct(Wallet $model)
    {
        $this->model = $model;
    }

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

    public function finalizeWallet(int $buyerWalletId, int $sellerWalletId, int $qty, float $price, float $feeBuyer, float $feeSeller): void {
        $totalCost = $price * $qty;

        Wallet::query()
            ->where('id', $buyerWalletId)
            ->update([
                'reserved_amount' => new Expression("reserved_amount - {$totalCost}"),
                'amount_balance'  => new Expression("amount_balance - {$totalCost} - {$feeBuyer}"),
                'gold_balance'    => new Expression("gold_balance + {$qty}"),
            ]);

        Wallet::query()
            ->where('id', $sellerWalletId)
            ->update([
                'reserved_gold'   => new Expression("reserved_gold - {$qty}"),
                'gold_balance'    => new Expression("gold_balance - {$qty}"),
                'amount_balance'  => new Expression("amount_balance + {$totalCost} - {$feeSeller}"),
            ]);
    }

    public function releaseReservation(int $walletId, string $orderType, float $remainingQty, float $price, float $estimatedFee = 0): void
    {
        if ($orderType === TradeTypes::Buy->value) {
            $totalReserved = ($price * $remainingQty) + $estimatedFee;

            Wallet::query()
                ->where('id', $walletId)
                ->decrement('reserved_amount', $totalReserved);
        }

        if ($orderType === TradeTypes::Sell->value) {
            Wallet::query()
                ->where('id', $walletId)
                ->decrement('reserved_gold', $remainingQty);
        }
    }

    public function addWallet(int $userId): Model
    {
        $data = ['user_id' => $userId];

        return $this->model->saveModel($data);
    }
}
