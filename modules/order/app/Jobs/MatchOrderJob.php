<?php

namespace Modules\order\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\commission\app\Services\CommissionService;
use Modules\order\app\Interfaces\OrderRepositoryInterface;
use Modules\trade\app\Enums\TradeTypes;
use Modules\trade\app\Interfaces\TradeRepositoryInterface;
use Modules\wallet\app\Interfaces\WalletRepositoryInterface;

class MatchOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $orderId
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(
        OrderRepositoryInterface $ordersRepository,
        TradeRepositoryInterface $tradesRepository,
        CommissionService        $commissionService,
        WalletRepositoryInterface  $walletRepository
    ): void
    {
        $incoming = $ordersRepository->find($this->orderId);
        if (!$incoming || $incoming->remaining_quantity <= 0) {
            return;
        }

        DB::transaction(function () use ($incoming, $ordersRepository, $tradesRepository, $commissionService, $walletRepository) {
            $book = $ordersRepository->findOpposingOrders($incoming->trade_type, $incoming->price);

            foreach ($book as $order) {
                if ($incoming->remaining_quantity <= 0) {
                    break;
                }

                $matchQty = min($incoming->remaining_quantity, $order->remaining_quantity);
                $tradePrice = $order->price;
                $feeBuyer = $commissionService->calculate(TradeTypes::Buy->value, $matchQty, $tradePrice);
                $feeSeller = $commissionService->calculate(TradeTypes::Sell->value, $matchQty, $tradePrice);

                $tradesRepository->create([
                    'buy_order_id' => $incoming->trade_type === TradeTypes::Buy->value ? $incoming->id : $order->id,
                    'sell_order_id' => $incoming->trade_type === TradeTypes::Sell->value ? $incoming->id : $order->id,
                    'price' => $tradePrice,
                    'quantity' => $matchQty,
                    'commission_buyer' => $feeBuyer,
                    'commission_seller' => $feeSeller,
                ]);

                $buyerWalletId = $incoming->trade_type === TradeTypes::Buy->value ? $incoming->user->wallet->id : $order->user->wallet->id;
                $sellerWalletId = $incoming->trade_type === TradeTypes::Sell->value ? $incoming->user->wallet->id : $order->user->wallet->id;
                $walletRepository->finalizeWallet($buyerWalletId, $sellerWalletId, $matchQty, $tradePrice, $feeBuyer, $feeSeller);
                $ordersRepository->decrementQuantity($incoming->id,$matchQty);
                $ordersRepository->decrementQuantity($order->id,$matchQty);
                $ordersRepository->updateStatus($order->id, $order->remaining_quantity);
            }

            $ordersRepository->updateStatus($incoming->id, $incoming->remaining_quantity);
        });
    }
}
