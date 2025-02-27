<?php

namespace Eloquent\LogSender\Console\Commands;

use Eloquent\LogSender\Http\LogSender;
use Eloquent\LogSender\Models\FailedLog;
use Illuminate\Console\Command;

class RetryFailedLogsCommand extends Command
{
    protected $signature = 'logs:retry {--limit=50}';
    protected $description = 'Retry sending failed logs to the logging server';

    public function handle()
    {
        $logSender = new LogSender();
    
        while ($batch = FailedLog::getBatch()) {
            if ($batch->isEmpty()) {
                break;
            }
    
            $batchData = $batch->map(function ($log) {
                return [
                    'id' => $log->id,
                    'level' => $log->level,
                    'message' => $log->message,
                    'context' => $log->context,
                    'environment' => $log->environment,
                ];
            })->toArray();
            
    
            try {
                $logSender->sendBatch($batchData);
                FailedLog::whereIn('id', $batch->pluck('id')->toArray())->update(['sent' => true]);
            } catch (\Exception $e) {
                $this->error("Batch retry failed.");
            }
        }
    }
    
}
