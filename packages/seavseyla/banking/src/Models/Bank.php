<?php

namespace SeavSeyla\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }
}
