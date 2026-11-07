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
        // Priority: DB_* variables > MYSQL_* variables > DATABASE_URL
        if (env('DB_HOST') && env('DB_DATABASE')) {
            // Use standard Laravel DB_* variables (highest priority)
            config([
                'database.default' => env('DB_CONNECTION', 'mysql'),
                'database.connections.mysql.host' => env('DB_HOST'),
                'database.connections.mysql.port' => env('DB_PORT', '3306'),
                'database.connections.mysql.database' => env('DB_DATABASE'),
                'database.connections.mysql.username' => env('DB_USERNAME', env('DB_USER', 'root')),
                'database.connections.mysql.password' => env('DB_PASSWORD'),
            ]);
        }
        elseif (env('MYSQLHOST') && env('MYSQLDATABASE')) {
            // Use Railway MySQL service MYSQL_* variables
            config([
                'database.default' => 'mysql',
                'database.connections.mysql.host' => env('MYSQLHOST'),
                'database.connections.mysql.port' => env('MYSQLPORT', '3306'),
                'database.connections.mysql.database' => env('MYSQLDATABASE'),
                'database.connections.mysql.username' => env('MYSQLUSER', 'root'),
                'database.connections.mysql.password' => env('MYSQLPASSWORD'),
            ]);
        }
        elseif ($databaseUrl = env('DATABASE_URL')) {
            // Fallback to DATABASE_URL (standard format)
            $url = parse_url($databaseUrl);

            if ($url) {
                $scheme = $url['scheme'] ?? 'mysql';

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
