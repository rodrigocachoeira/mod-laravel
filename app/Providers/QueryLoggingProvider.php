<?php

namespace App\Providers;

use Monolog\Handler\RotatingFileHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class QueryLoggingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('local')) {
            DB::listen(function ($query) {
                $dbLog = new Logger('Query');
                $dbLog->pushHandler(new RotatingFileHandler(
                    storage_path('logs/queries.log'), 5, Logger::DEBUG)
                );
                $dbLog->info($query->sql, [
                    'Bindings' => $query->bindings,
                    'Time' => $query->time]
                );
            });
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
