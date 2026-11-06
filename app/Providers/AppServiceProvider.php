<?php

namespace App\Providers;

use App\Contracts\WhatsAppSender;
use App\Services\WhatsApp\FonnteWhatsAppSender;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppSender::class, FonnteWhatsAppSender::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Parse DATABASE_URL from Railway and set individual env vars
        if ($databaseUrl = env('DATABASE_URL')) {
            $url = parse_url($databaseUrl);

            if ($url) {
                // Determine database type from scheme
                $scheme = $url['scheme'] ?? 'mysql';

                // Set individual database configuration
                if ($scheme === 'mysql' || $scheme === 'mariadb') {
                    putenv('DB_CONNECTION=mysql');
                    putenv('DB_HOST=' . ($url['host'] ?? '127.0.0.1'));
                    putenv('DB_PORT=' . ($url['port'] ?? '3306'));
                    putenv('DB_DATABASE=' . ltrim($url['path'] ?? 'laravel', '/'));
                    putenv('DB_USERNAME=' . ($url['user'] ?? 'root'));
                    putenv('DB_PASSWORD=' . ($url['pass'] ?? ''));
                } elseif ($scheme === 'postgresql' || $scheme === 'postgres') {
                    putenv('DB_CONNECTION=pgsql');
                    putenv('DB_HOST=' . ($url['host'] ?? '127.0.0.1'));
                    putenv('DB_PORT=' . ($url['port'] ?? '5432'));
                    putenv('DB_DATABASE=' . ltrim($url['path'] ?? 'laravel', '/'));
                    putenv('DB_USERNAME=' . ($url['user'] ?? 'root'));
                    putenv('DB_PASSWORD=' . ($url['pass'] ?? ''));
                }
            }
        }
    }
}
