<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pode criar, atualizar e deletar produtos
        // Can create product, update and delete
        Gate::define('user_admin', function(User $user){
            return $user->role === 'admin';
        });
    }
}
