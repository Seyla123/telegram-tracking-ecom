<?php

namespace SeavSeyla\Banking\Services;

use SeavSeyla\Banking\Models\Checkout;
use SeavSeyla\Banking\Models\Transaction;
use SeavSeyla\Banking\Repositories\CheckoutRepository;

class CheckoutService
{
    private CheckoutRepository $repository;
    public function __construct(CheckoutRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * create checkout for confirm transaction
     * @param Transaction $transaction
     * @return Checkout
     */
    public function createCheckout(Transaction $transaction): Checkout
    {
        return $this->repository->create(
            [
                'transaction_id' => $transaction->id,
                'otp_code' => '111111',
                'expired_at' => now()->addMinutes(5)
            ]
        );
    }

    /**
     * verify otp checkout 
     * @param Checkout $checkout
     * @param string $otp
     * @throws \Exception
     * @return Checkout
     */
    public function verifyOtpCheckout(Checkout $checkout, string $otp): Checkout
    {
        // Check for pending status and OTP validity
        if ($checkout->status !== 'pending' || $checkout->expired_at < now() || !$checkout->otp_code) {
            throw new \Exception(__('checkout_already_used_or_expired'));
        }

        if ($checkout->otp_code != $otp) {
            throw new \Exception(__('otp_incorrect'));
        }
        return $checkout;
    }

    /**
     * check if checkout exists or expired
     * @param Transaction $transaction
     * @throws \Exception
     * @return Checkout
     */
    public function checkIfCheckoutExistsOrExpired(Transaction $transaction): Checkout
    {
        $checkout = $transaction->checkout;

        if (!$checkout) {
            throw new \Exception(__('checkout_not_found'));
        }
        if ($checkout->status !== 'pending') {
            throw new \Exception(__('checkout_already_used'));
        }
        if ($checkout->expired_at < now()) {
            throw new \Exception(__('checkout_expired'));
        }

        return $checkout;
    }

    /**
     * confirmCheckout , atfer confirm transaction update checkout status and set otp_code to null
     * @param Checkout $checkout
     * @return bool
     */
    public function confirmCheckout(Checkout $checkout)
    {
        return $this->repository->update($checkout, [
            'status' => 'completed',
            'otp_code' => null
        ]);
    }
}