<?php

namespace SeavSeyla\Banking\Livewire;

use SeavSeyla\Banking\Models\Bank;
use SeavSeyla\Banking\Services\BankAccountService;
use SeavSeyla\Banking\Validations\AddBankAccountValidateRules;
use Livewire\Attributes\Title;
use Livewire\Component;

class AddBankAccount extends Component
{
    #[Title('បញ្ជូលគណនី')]
    public $selectedBank;
    public $bankAccountNumber;
    private BankAccountService $bankAccountService;
    public function boot(BankAccountService $bankAccountService)
    {
        $this->bankAccountService = $bankAccountService;
    }
    public function addBankAccount(): void
    {
        // validate data
        $validated = $this->validate(AddBankAccountValidateRules::rules());

        //pass data to BankAccountService
        $bankAccountCreated = $this->bankAccountService->createBankAccount($validated);

        // if created successfully, redirect to withdraw page
        if ($bankAccountCreated) {
            $this->redirect('/withdraw', navigate: true);
            return;
        }
        // if failed, redirect back to add bank account page
        $this->redirect('/add-bank-account', navigate: true);

    }
    public function render()
    {
        $banks = Bank::all();
        return view('livewire.pages.add-bank-account', [
            'banks' => $banks
        ]);
    }
}
