<?php

namespace Modules\order\app\Services\Validations;

use Illuminate\Validation\ValidationException;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;

class WalletValidationService
{
    private WalletRepositoryInterface $walletRepository;

    public function __construct(
        WalletRepositoryInterface  $walletRepository,
    ) {
        $this->walletRepository = $walletRepository;
    }

    public function ensureSufficientFunds($wallet, float $price, float $quantity, float $fee): void
    {
        $required  = ($price * $quantity) + $fee;
        $reserved  = $this->walletRepository->sumReservedAmount($wallet->id);
        $available = $wallet->amount_balance - $reserved;
        if ($available < $required) {
            throw ValidationException::withMessages(['funds' => 'Insufficient funds']);
        }
        $this->walletRepository->reserveAmount($wallet->id, $required);
    }

    public function ensureSufficientGold($wallet, float $quantity): void
    {
        $reserved  = $this->walletRepository->sumReservedGold($wallet->id);
        $available = $wallet->gold_balance - $reserved;
        if ($available < $quantity) {
            throw ValidationException::withMessages(['gold' => 'Insufficient gold balance']);
        }
        $this->walletRepository->reserveGold($wallet->id, $quantity);
    }
}
