<?php

namespace SeavSeyla\Banking\Services;

use SeavSeyla\Banking\Models\BankAccount;
use SeavSeyla\Banking\Models\Transaction;
use SeavSeyla\Banking\Repositories\TransactionRepository;
use SeavSeyla\Banking\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankTransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private WalletRepository $walletRepository,
        private WalletService $walletService
    ) {
    }

    /**
     * Process a bank deposit transaction
     * @param array $data
     * @return Transaction
     */
    public function processDeposit(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Create transaction record
            $transaction = $this->transactionRepository->create([
                'amount' => $data['amount'],
                'transaction_type' => 'deposit',
                'bank_account_id' => $data['bank_account_id'],
                'source_wallet_id' => $data['wallet_id'],
                'user_id' => auth()->id(),
                'reference_code' => Str::uuid(),
                'status' => 'completed'
            ]);

            // Update wallet balance
            $this->walletService->depositFunds($data['amount']);

            return $transaction;
        });
    }

    /**
     * Process a bank withdrawal transaction
     * @param array $data
     * @return Transaction
     */
    public function processWithdrawal(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Check sufficient balance
            $this->walletService->hasSufficientBalance($data['amount']);

            // Create transaction record
            $transaction = $this->transactionRepository->create([
                'amount' => $data['amount'],
                'transaction_type' => 'withdrawal',
                'bank_account_id' => $data['bank_account_id'],
                'source_wallet_id' => $data['wallet_id'],
                'user_id' => auth()->id(),
                'reference_code' => Str::uuid(),
                'status' => 'completed'
            ]);

            // Update wallet balance
            $this->walletService->withdrawFunds($data['amount']);

            return $transaction;
        });
    }

    /**
     * Get transaction history for a wallet
     * @param int $walletId
     * @return array
     */
    // public function getTransactionHistory(int $walletId): array
    // {
    //     return $this->transactionRepository->getWalletTransactions($walletId);
    // }

    /**
     * Validate bank account ownership
     * @param int $bankAccountId
     * @return bool
     */
    public function validateBankAccount(int $bankAccountId): bool
    {
        return BankAccount::where('id', $bankAccountId)
            ->where('user_id', auth()->id())
            ->exists();
    }
}