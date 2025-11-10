<?php

namespace App\Providers;

use App\Http\Repository\ClientRepository;
use App\Models\Compte;
use App\Observers\CompteObserver;
use App\Http\Repository\CompteRepo;
use App\Http\Services\CompteService;
use Illuminate\Support\ServiceProvider;
use App\Http\Repository\UserRepository;
use App\Http\Services\ClientService;
use App\Http\Services\UserService;
use App\Http\Services\SmsService;
use App\Interfaces\RepositoriesInterfaces\CompteRepositoryInterface;
use App\Interfaces\RepositoriesInterfaces\IFirstOrCreateRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CompteRepositoryInterface::class, function ($app) {
            return new CompteRepo(new Compte());
        });
        
        $this->app->singleton(CompteService::class, function ($app) {
            return new CompteService($app->make(CompteRepositoryInterface::class), $app->make(UserService::class), $app->make(ClientService::class));
        });

        $this->app->when(UserService::class)->needs(IFirstOrCreateRepository::class)->give(UserRepository::class);

        $this->app->when(ClientService::class)->needs(IFirstOrCreateRepository::class)->give(ClientRepository::class);

        $this->app->singleton(UserService::class);
        $this->app->singleton(ClientService::class);
        $this->app->singleton(SmsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Compte::observe(CompteObserver::class);
    }
}
