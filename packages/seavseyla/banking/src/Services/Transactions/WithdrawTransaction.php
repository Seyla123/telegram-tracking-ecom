<?php 

namespace SeavSeyla\Banking\Services\Transactions;

use SeavSeyla\Banking\Interfaces\TransactionInterface;
use SeavSeyla\Banking\Models\Transaction;
use SeavSeyla\Banking\Repositories\TransactionRepository;

class WithdrawTransaction implements TransactionInterface
{
    private TransactionRepository $repository;
    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }
    public function process(array $data): Transaction
    {
        return $this->repository->create([
            'amount' => $data['amount'],
            'transaction_type' => 'withdrawal',
            'bank_account_id' => $data['selectedBankAccount'],
            'source_wallet_id' => $data['walletId'],
            'reference_code' => $data['reference_code'],
        ]);
    }
}