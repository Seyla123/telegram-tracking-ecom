<?php
namespace SeavSeyla\Banking\Validations;

class WithdrawValidateRules
{
    public static function rules(): array
    {
        return [
            'amount' => 'required|numeric|lte:' . auth()->user()->wallet->first()->balance,
            'selectedBankAccount' => 'required|exists:bank_accounts,id',
            'walletId' => 'required|exists:wallets,id',
        ];
    }

    public static function messages(): array
    {
        return [
            'amount.required' => 'សូមបញ្ជូលចំនួនទឹកប្រាក់ជាមុនសិន !',
            'amount.lte' => __('insufficient_funds'),
            'selectedBankAccount.required' => 'សូមជ្រើសរើសគណនីធនាគារ',
        ];
    }
}
