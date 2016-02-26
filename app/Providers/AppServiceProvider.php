<?php

namespace App\Providers;

use App\Repositories\BankRepository;
use App\Repositories\EloquentBankRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class,EloquentUserRepository::class);
        $this->app->bind(BankRepository::class,EloquentBankRepository::class);
    }
}
