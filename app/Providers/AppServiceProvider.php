<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Configurar public_html como carpeta pública
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });

        // Override del public_path para usar public_html
        $this->app->usePublicPath(base_path('public_html'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
