<?php 

namespace SeavSeyla\Banking\Services\Transactions;

use SeavSeyla\Banking\Interfaces\TransactionInterface;
use SeavSeyla\Banking\Repositories\TransactionRepository;

class TransferTransaction implements TransactionInterface
{
    private TransactionRepository $repository;
    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }
    public function process(array $data)
    {
        return $this->repository->create([
            'amount' => $data['amount'],
            'user_id' => $data['user_id'],
            'transaction_type' => 'transfer',
            'destination_wallet_id' => $data['destination_wallet_id'],
            'source_wallet_id' => $data['walletId'],
            'reference_code' => $data['reference_code'],
        ]);
    }
}