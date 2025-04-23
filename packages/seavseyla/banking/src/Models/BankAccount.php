<?php

namespace SeavSeyla\Banking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'bank_id',
        'user_id',
        'account_number',
        'account_name'
    ];
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function primaryBankAccount(): HasOne
    {
        return $this->hasOne(PrimaryBankAccount::class);
    }
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    

}
