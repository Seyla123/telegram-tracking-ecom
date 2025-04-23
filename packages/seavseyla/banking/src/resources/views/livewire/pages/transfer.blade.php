<div class="max-w-2xl mx-auto space-y-6" x-data="{
    amount: $wire.amount == 0 ? '' : $wire.amount,
    errors: {
        amount: '',
        recipient: ''
    },
    validateAmount() {
        if (!this.amount || this.amount == 0) {
            this.errors.amount = '{{ __('amount_required') }}';
            return false;
        }
        this.errors.amount = '';
        return true;
    },
    validate() {
        return this.validateAmount();
    },
    submitForm() {
        if (this.validate()) {
            $wire.set('amount', this.amount);
            $wire.save();
        }
    },
    addAmount(value) {
        this.amount = (parseFloat(this.amount || 0) + value).toFixed(2);
    }
}">
    {{-- Header --}}
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('transfer_funds') }}</h1>
    </x-slot>

    {{-- Wallet Card --}}
    <x-wallet-card :walletNumber="$wallet->wallet_number" :balance="$wallet->balance" :username="auth()->user()->name" />

    {{-- Transfer Form --}}
    <section class="bg-white rounded-xl p-6 shadow-sm space-y-8">
        <div class="border-b pb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('transfer_details') }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ __('Please fill in the transfer details below') }}</p>
        </div>

        {{-- Recipient Search --}}
        <div class="space-y-3">
            <x-input-label for="recipientWalletNumber" value="{{ __('recipient_wallet_number') }}" class="text-base" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                    </svg>
                </div>
                <x-text-input wire:model.live="recipientWalletNumber" wire:change="findRecipient"
                    id="recipientWalletNumber" type="text" class="w-full pl-10 pr-4"
                    placeholder="Enter recipient's wallet number" />
                @if ($recipient)
                    <div class="mt-3 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">{{ __('recipient_found') }}</p>
                            <p class="text-sm text-green-700">{{ $recipient->user->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
            @error('recipientWalletNumber')
                <p class="text-sm text-red-600 flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Amount Input --}}
        <div class="space-y-3">
            <x-input-label for="amount" value="{{ __('amount') }}" class="text-base" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 text-lg">$</span>
                </div>
                <x-text-input x-model="amount" type="number" id="amount" class="w-full pl-8 pr-4 text-lg"
                    placeholder="0.00" step="0.01" min="0" />
            </div>
            <p x-show="errors.amount" x-text="errors.amount" class="text-sm text-red-600 flex items-center">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span x-text="errors.amount"></span>
            </p>
            @error('amount')
                <p class="text-sm text-red-600 flex items-center">
                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Quick Amount Buttons --}}
        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">{{ __('Quick Amount') }}</label>
            <div class="grid grid-cols-4 gap-3">
                @foreach ([10, 20, 50, 100] as $quickAmount)
                    <button type="button" @click="addAmount({{ $quickAmount }})"
                        class="py-2 px-4 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 
                               transition-colors duration-200 font-medium text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        ${{ $quickAmount }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="pt-6">
            <x-primary-button @click="submitForm()" wire:loading.attr="disabled"
                class="w-full justify-center py-3 text-lg transition-transform duration-200 transform hover:scale-[1.02]">
                <span wire:loading wire:target="save" class="mr-2">
                    <x-loading-spinner class="h-5 w-5" />
                </span>
                <span wire:loading.remove wire:target="save">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                {{ __('transfer_now') }}
            </x-primary-button>
        </div>
    </section>

    {{-- Error Modal --}}
    @if (session('error'))
        <x-modal-error />
    @endif
</div>
