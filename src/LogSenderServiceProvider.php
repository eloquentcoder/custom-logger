<?php

namespace Vendor\CustomLogger;

use Eloquent\LogSender\Console\Commands\RetryFailedLogsCommand;
use Eloquent\LogSender\Models\FailedLog;
use Illuminate\Support\ServiceProvider;

class CustomLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/customlogger.php' => config_path('customlogger.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Allow users to publish the migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'logsender-migrations');


    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/customlogger.php', 'customlogger'
        );

        $this->commands([
            RetryFailedLogsCommand::class,
        ]);

    }
}
