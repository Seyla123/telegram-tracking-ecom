<?php 

namespace SeavSeyla\Banking\Repositories;

use Illuminate\Support\Facades\Auth;
use SeavSeyla\Banking\Interfaces\BankAccountRepositoryInterface;
use SeavSeyla\Banking\Models\BankAccount;

class BankAccountRepository implements BankAccountRepositoryInterface
{
    public function create($data): BankAccount
    {
        return Auth::user()->bankAccounts()->create($data);
    }
    public function delete(BankAccount $bankAccount): bool
    {
       return $bankAccount->delete();
    }
    public function find(string|int $id): ?BankAccount
    {
        return Auth::user()->bankAccounts->find($id);
    }
}