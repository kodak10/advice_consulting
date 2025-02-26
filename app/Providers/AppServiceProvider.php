<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Partager les notifications avec toutes les vues
        View::composer('*', function ($view) {
            if (Auth::check()) { // Vérifier si l'utilisateur est connecté
                $notifications = Auth::user()->unreadNotifications;
                $view->with('notifications', $notifications);
            }
        });
    }
}
