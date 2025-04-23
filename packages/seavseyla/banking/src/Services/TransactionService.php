<?php

namespace SeavSeyla\Banking\Services;

use SeavSeyla\Banking\Interfaces\TransactionInterface;
use SeavSeyla\Banking\Models\Transaction;
use SeavSeyla\Banking\Repositories\TransactionRepository;
use SeavSeyla\Banking\Services\Transactions\DepositTransaction;
use SeavSeyla\Banking\Services\Transactions\TransferTransaction;
use SeavSeyla\Banking\Services\Transactions\WithdrawTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class TransactionService
{

    public function __construct(
        private TransactionRepository $repository,
        private WalletService $walletService,
        private DepositTransaction $depositTransaction,
        private WithdrawTransaction $withdrawTransaction,
        private TransferTransaction $transferTransaction,
        private CheckoutService $checkoutService
    ) {
    }

    /**
     * create transaction base on transaction type ('deposit', 'withdrawal', 'transfer')
     * @param array $data
     * @param string $transactionType type of transaction ('deposit', 'withdrawal', 'transfer')
     * @return ?Transaction
     */
    public function createTransaction(array $data, string $transactionType): ?Transaction
    {
        try {
            //prepare data 
            $data = array_merge($data, [
                'reference_code' => $this->generateReferenceCode(),
                'transaction_type' => $transactionType,
                'user_id' => Auth::id(), // Add user_id to data
            ]);

            // Check if the wallet has sufficient balance for (withdrawal, transfer)
            if ($transactionType == 'withdrawal' || $transactionType == 'transfer') {
                $this->walletService->hasSufficientBalance($data['amount']);
            }

            // Determine which transaction type to create
            $transaction = $this->getTransactionInstance($transactionType);

            // Process the transaction and get the created transaction
            return $transaction->process($data);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Transaction creation failed: ' . $e->getMessage());

            if (config('app.env') == 'production') {
                session()->flash('fail', __('operation_failed'));
            } else {
                session()->flash('fail', $e->getMessage());
            }

            return null; // Explicitly return null on failure
        }
    }

    /**
     * Create a withdrawal transaction
     * @param array $data
     * @return ?Transaction
     */
    public function createWithdrawTransaction(array $data): ?Transaction
    {
        return $this->createTransaction($data, 'withdrawal');
    }

    /**
     * get transaction instance ('deposit', 'withdrawal', 'transfer')
     * @param string $transactionType
     * @throws \InvalidArgumentException
     * @return TransactionInterface
     */
    private function getTransactionInstance(string $transactionType): TransactionInterface
    {
        switch ($transactionType) {
            case 'deposit':
                return $this->depositTransaction;
            case 'withdrawal':
                return $this->withdrawTransaction;
            case 'transfer':
                return $this->transferTransaction;
            default:
                throw new \InvalidArgumentException('Transaction type មិនត្រឹមត្រូវទេ');
        }
    }

    /**
     * gerate unique reference code
     * @return string
     */
    public function generateReferenceCode(): string
    {
        return Str::uuid();
    }

    /**
     * confirm transaction ,update wallet balance and update transaction status and checkout status
     * @param  $transaction
     * @throws \Exception
     * @return void
     */
    public function confirmTransaction(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            if (!$transaction) {
                throw new \Exception('រកមិនឃើញ transaction មួយនេះទេ');
            }

            // update balance in wallet
            $this->walletService->updateBanlance($transaction);

            // update transaction status
            if (!$this->repository->update($transaction, ['status' => 'completed'])) {
                throw new \Exception('រកមិនឃើញ transaction មួយនេះទេ');
            }

            // update checkout status
            $this->checkoutService->confirmCheckout($transaction->checkout);

            DB::commit();

        } catch (\Throwable $th) {

            DB::rollBack();
            throw new \Exception($th->getMessage());
        }

    }

    /**
     * check transaction exists or checkout exists or expired
     * @param string $referenceCode
     * @throws \Exception
     * @return Transaction
     */
    public function checkTransaction(string $referenceCode): Transaction
    {
        // check if transaction exists
        $transaction = $this->repository->findByReferenceCode($referenceCode);

        if (!$transaction) {
            throw new \Exception('រកមិនឃើញ transaction មួយនេះទេ');
        }

        return $transaction;
    }

}
