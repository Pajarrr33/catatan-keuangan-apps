<?php

namespace App\Providers;

use App\Services\Impl\TransactionServiceImpl;
use App\Services\TransactionService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function provides(): array
    {
        return [TransactionService::class];
    }
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TransactionService::class, TransactionServiceImpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
