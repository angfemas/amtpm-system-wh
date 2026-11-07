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
        // Configure database connection from Railway MySQL service
        // Railway provides MYSQL_* variables for MySQL service
        if (env('MYSQLHOST') && env('MYSQLDATABASE')) {
            config([
                'database.default' => 'mysql',
                'database.connections.mysql.host' => env('MYSQLHOST'),
                'database.connections.mysql.port' => env('MYSQLPORT', '3306'),
                'database.connections.mysql.database' => env('MYSQLDATABASE'),
                'database.connections.mysql.username' => env('MYSQLUSER', 'root'),
                'database.connections.mysql.password' => env('MYSQLPASSWORD'),
            ]);
        }
        // Fallback to DATABASE_URL (standard format)
        elseif ($databaseUrl = env('DATABASE_URL')) {
            $url = parse_url($databaseUrl);

            if ($url) {
                // Determine database type from scheme
                $scheme = $url['scheme'] ?? 'mysql';

                // Configure database connection directly
                config([
                    'database.default' => $scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql',
                    'database.connections.' . ($scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql') . '.host' => $url['host'] ?? '127.0.0.1',
                    'database.connections.' . ($scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql') . '.port' => $url['port'] ?? ($scheme === 'postgresql' || $scheme === 'postgres' ? '5432' : '3306'),
                    'database.connections.' . ($scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql') . '.database' => ltrim($url['path'] ?? 'laravel', '/'),
                    'database.connections.' . ($scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql') . '.username' => $url['user'] ?? 'root',
                    'database.connections.' . ($scheme === 'postgresql' || $scheme === 'postgres' ? 'pgsql' : 'mysql') . '.password' => $url['pass'] ?? '',
                ]);
            }
        }
    }
}
