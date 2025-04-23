<?php

namespace SeavSeyla\Banking\Services;

use SeavSeyla\Banking\Models\Transaction;
use SeavSeyla\Banking\Models\Wallet;
use SeavSeyla\Banking\Repositories\WalletRepository;

class WalletService
{
    private WalletRepository $repository;
    public function __construct(WalletRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * update wallet balance base on transaction type (withdrawal, deposit, transfer)
     * @param Transaction $transaction
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return void
     */
    public function updateBanlance(Transaction $transaction): void
    {

        switch ($transaction->transaction_type) {
            case 'withdrawal':
                $this->withdrawFunds($transaction->amount);
                break;
            case 'deposit':
                $this->depositFunds($transaction->amount);
                break;
            case 'transfer':
                $this->transferFunds($transaction->amount);
                break;
            default:
                throw new \InvalidArgumentException('Invalid transaction type');
        }
    }
    /**
     * withdraw funds and update (subtract) wallet balance
     * @param float $amount
     * @throws \Exception
     * @return void
     */
    public function withdrawFunds(float $amount): void
    {
        // check if wallet has sufficient balance
        $this->hasSufficientBalance($amount);
        // update (subtract) wallet balance
        $this->repository->withdraw($amount);
    }
    /**
     * deposit funds and update (add) wallet balance
     * @param float $amount
     * @return void
     */
    public function depositFunds(float $amount): void
    {
        $this->repository->deposit($amount);
    }
    /**
     * transfer funds and update (subtract) wallet balance
     * @param float $amount
     * @throws \Exception
     * @return void
     */
    public function transferFunds(float $amount, ?int $destinationWalletId = null): void
    {
        // check if wallet has sufficient balance
        $this->hasSufficientBalance($amount);

        // update (subtract) wallet balance
        $this->repository->transfer($amount);

        // If destination wallet is provided, update its balance
        if ($destinationWalletId) {
            $destinationWallet = Wallet::find($destinationWalletId);
            if ($destinationWallet) {
                $destinationWallet->balance += $amount;
                $destinationWallet->save();
            }
        }
    }
    public function hasSufficientBalance(float $amount)
    {
        if ($amount > $this->repository->currentBalance()) {
            throw new \Exception(__('insufficient_funds'));
        }
        return true;
    }
}