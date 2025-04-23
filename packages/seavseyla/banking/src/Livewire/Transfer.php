<?php

namespace SeavSeyla\Banking\Livewire;

use SeavSeyla\Banking\Services\TransactionService;
use SeavSeyla\Banking\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Transfer extends Component
{
    public $amount = 0;
    public $recipientWalletNumber = '';
    public $wallet;
    public $recipient;

    public function mount()
    {
        $this->wallet = Auth::user()->wallet->first();
    }

    public function findRecipient()
    {
        $this->recipient = Wallet::where('wallet_number', $this->recipientWalletNumber)
            ->where('id', '!=', $this->wallet->id)
            ->where('is_active', true)
            ->first();
    }

    public function save(TransactionService $transactionService)
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:1', 'lte:' . $this->wallet->balance],
            'recipientWalletNumber' => ['required', 'exists:wallets,wallet_number,is_active,1']
        ]);

        if (!$this->recipient) {
            $this->findRecipient();
        }

        try {
            $transactionService->createTransaction([
                'amount' => $this->amount,
                'walletId' => $this->wallet->id,
                'destination_wallet_id' => $this->recipient->id,
            ], 'transfer');

            session()->flash('success', 'Transfer completed successfully!');
            return redirect()->route('wallet');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.transfer');
    }
}