<?php

return [
    'endpoint' => env('LOG_MONITOR_URL', 'https://your-log-server.com/api/logs'),
    'queue_logs' => env('LOG_QUEUE', true),
    'log_retries' => env('LOG_RETRIES', 3),
    'log_backoff' => [2, 5, 10, 20], // Custom retry delays
    'log_batch_size' => env('LOG_BATCH_SIZE', 50),
    'project' => env('LOG_PROJECT_SLUG', 'laravel'),
];
