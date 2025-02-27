<?php

namespace EloquentCoder\LogSender\Logging;

use EloquentCoder\LogSender\Http\LogSender as HttpLogSender;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use EloquentCoder\LogSender\Jobs\SendLogJob;

class CustomLogger extends AbstractProcessingHandler
{
    protected $logSender;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        $this->logSender = new HttpLogSender();
    }

    protected function write(LogRecord $record): void
    {
        $logData = [
            'level' => $record['level_name'],
            'message' => $record['message'],
            'context' => $record['context'],
            'environment' => config('app.env'),
            'project' => config('customlogger.project'),
            'app_name' => config('app.name'),
        ];

        if (config('customlogger.queue_logs')) {
            dispatch(new SendLogJob($logData));
        } else {
            $this->logSender->send($logData);
        }
    }
}
