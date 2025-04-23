<?php

namespace SeavSeyla\Banking\Repositories;

use SeavSeyla\Banking\Interfaces\TransactionRepositoryInterface;
use SeavSeyla\Banking\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Auth::user()->transactions()->create($data);
    }
    public function find(string|int $id): Transaction
    {
        return Transaction::findOrFail($id);
    }
    public function update(Transaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }
    public function findByReferenceCode(string $referenceCode): Transaction
    {
        // check if transaction exists
        $transaction = Auth::user()->transactions->where('reference_code', $referenceCode)->first();

        return $transaction;
    }
}