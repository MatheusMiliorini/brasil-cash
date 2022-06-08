<?php

namespace App\Providers;

use App\Repositories\CardRepository;
use App\Repositories\CardRepositoryImpl;
use App\Repositories\TransactionRepository;
use App\Repositories\TransactionRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CardRepository::class, CardRepositoryImpl::class);
        $this->app->bind(TransactionRepository::class, TransactionRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
