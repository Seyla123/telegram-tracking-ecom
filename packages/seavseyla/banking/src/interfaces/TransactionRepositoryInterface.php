<?php 

namespace SeavSeyla\Banking\Interfaces;

use SeavSeyla\Banking\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function find(string|int $id): Transaction;

}