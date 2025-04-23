<?php

namespace SeavSeyla\Banking\Providers;

use SeavSeyla\Banking\Repositories\TransactionRepository;
use SeavSeyla\Banking\Repositories\WalletRepository;
use SeavSeyla\Banking\Services\CheckoutService;
use SeavSeyla\Banking\Services\Transactions\DepositTransaction;
use SeavSeyla\Banking\Services\Transactions\TransferTransaction;
use SeavSeyla\Banking\Services\Transactions\WithdrawTransaction;
use SeavSeyla\Banking\Services\TransactionService;
use SeavSeyla\Banking\Services\WalletService;
use Illuminate\Support\ServiceProvider;

class BankingPackageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WalletRepository::class, function ($app) {
            return new WalletRepository();
        });

        $this->app->singleton(WalletService::class, function ($app) {
            return new WalletService($app->make(WalletRepository::class));
        });

        $this->app->singleton(TransactionRepository::class, function ($app) {
            return new TransactionRepository();
        });

        $this->app->singleton(TransactionService::class, function ($app) {
            return new TransactionService(
                $app->make(TransactionRepository::class),
                $app->make(WalletService::class),
                $app->make(DepositTransaction::class),
                $app->make(WithdrawTransaction::class),
                $app->make(TransferTransaction::class),
                $app->make(CheckoutService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}

