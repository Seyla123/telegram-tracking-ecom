<?php

namespace SeavSeyla\Banking\Livewire;

use SeavSeyla\Banking\Repositories\WalletRepository;
use SeavSeyla\Banking\Services\BankAccountService;
use SeavSeyla\Banking\Services\CheckoutService;
use SeavSeyla\Banking\Services\TransactionService;
use SeavSeyla\Banking\Validations\WithdrawValidateRules;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Withdraw extends Component
{
    #[Title('ដកប្រាក់')]
    public $amount = 0;
    public $selectedBankAccount;
    public $walletId;
    private BankAccountService $bankAccountService;
    private TransactionService $transactionService;
    private WalletRepository $walletRepository;
    public function boot(
        BankAccountService $bankAccountService,
        TransactionService $transactionService,
        WalletRepository $walletRepository,
        CheckoutService $checkoutService
    ) {
        $this->bankAccountService = $bankAccountService;
        $this->transactionService = $transactionService;
        $this->walletRepository = $walletRepository;
    }
    public function save()
    {
        // validate data
        $validated = $this->validate(
            WithdrawValidateRules::rules(),
            WithdrawValidateRules::messages()
        );

        // pass data to TransactionService to create withdraw transaction
        $transaction = $this->transactionService->createWithdrawTransaction($validated);

        // if success redirect to send otp option to verify code 
        $this->redirect(route('send-otp-option', [
            'transaction' => $transaction->reference_code
        ]), navigate: true);

    }
    #[On('delete-bank-account')]
    public function deleteBankAccount($id)
    {
        //pass data to BankAccountService
        $this->bankAccountService->deleteBankAccount($id);

        $this->redirect('/withdraw', navigate: true);
    }
    public function render()
    {
        $user = Auth::user()->load([
            'bankAccounts.bank',
            'primaryBankAccount'
        ]);

        // if primary bank account is not set, set it to the first bank account
        $primaryBankAccount = $user->primaryBankAccount?->bank_account_id ?? $user->bankAccounts->first()?->id;

        // get wallet 
        $wallet = $this->walletRepository->getWallet();

        // set wallet id
        $this->walletId = $wallet->id;

        return view('livewire.pages.withdraw', [
            'wallet' => $wallet,
            'bankAccounts' => $user->bankAccounts,
            'primaryBankAccount' => $primaryBankAccount,
        ]);
    }
}
