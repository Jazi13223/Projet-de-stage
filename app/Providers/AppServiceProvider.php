<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


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
       
// Injection dynamique du menu en fonction du rôle
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $role = Auth::user()->role;

            $menu = match ($role) {
                'admin'      => config('menus.admin.menu'),
                'secretaire' => config('menus.secretaire.menu'),
                'etudiant', 'etudiants' => config('menus.etudiants.menu'),
                default      => [],
            };

            // On écrase dynamiquement la config du menu AdminLTE
            Config::set('adminlte.menu', $menu);
        }
    });
    }
}