<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">{{ __('transaction_history') }}</h2>
        <div class="flex gap-2">
            <select wire:model.live="filter"
                class="rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="all">{{ __('all_transactions') }}</option>
                <option value="deposit">{{ __('deposits') }}</option>
                <option value="withdrawal">{{ __('withdrawals') }}</option>
                <option value="transfer">{{ __('transfers') }}</option>
            </select>
            <select wire:model.live="sort"
                class="rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="latest">{{ __('latest_first') }}</option>
                <option value="oldest">{{ __('oldest_first') }}</option>
                <option value="highest">{{ __('highest_amount') }}</option>
                <option value="lowest">{{ __('lowest_amount') }}</option>
            </select>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($transactions as $transaction)
            <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex items-start gap-3">
                        <div
                            class="@if ($transaction->transaction_type === 'deposit') bg-green-100 @elseif($transaction->transaction_type === 'withdrawal') bg-red-100 @else bg-blue-100 @endif p-2 rounded-full">
                            <svg class="w-5 h-5 @if ($transaction->transaction_type === 'deposit') text-green-600 @elseif($transaction->transaction_type === 'withdrawal') text-red-600 @else text-blue-600 @endif"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if ($transaction->transaction_type === 'deposit')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                @elseif($transaction->transaction_type === 'withdrawal')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">
                                @if ($transaction->transaction_type === 'deposit')
                                    {{ __('deposit_from_bank') }}
                                @elseif($transaction->transaction_type === 'withdrawal')
                                    {{ __('withdrawal_to_bank') }}
                                @else
                                    {{ __('transfer_to') }}
                                    {{ optional($transaction->destinationWallet->user)->name }}
                                @endif
                            </p>
                            <div class="text-xs text-gray-500 space-y-1">
                                <p>{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                                <p>{{ __('reference') }}: {{ $transaction->reference_code }}</p>
                                @if ($transaction->bank_account_id)
                                    <p>{{ __('bank_account') }}:
                                        {{ optional($transaction->bankAccount)->account_number }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <span
                        class="@if ($transaction->transaction_type === 'deposit') text-green-600 @elseif($transaction->transaction_type === 'withdrawal') text-red-600 @else text-blue-600 @endif font-medium">
                        {{ $transaction->transaction_type === 'withdrawal' ? '-' : '+' }}${{ number_format($transaction->amount, 2) }}
                    </span>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium @if ($transaction->status === 'completed') bg-green-100 text-green-800 @elseif($transaction->status === 'failed') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-gray-500">{{ __('no_transactions_found') }}</p>
            </div>
        @endforelse
    </div>

    @if ($transactions->hasPages())
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
