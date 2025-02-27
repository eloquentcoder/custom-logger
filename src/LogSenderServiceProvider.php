<?php

namespace EloquentCoder\LogSender;

use EloquentCoder\LogSender\Console\Commands\RetryFailedLogsCommand;
use Illuminate\Support\ServiceProvider;

class LogSenderServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../config/customlogger.php' => config_path('customlogger.php'),
        ], 'log-sender');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Allow users to publish the migrations
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'logsender-migrations');


    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/customlogger.php', 'log-sender'
        );

        $this->commands([
            RetryFailedLogsCommand::class,
        ]);

    }
}
