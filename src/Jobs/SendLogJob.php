<?php

namespace EloquentCoder\LogSender\Jobs;

use EloquentCoder\LogSender\Http\LogSender as HttpLogSender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class SendLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $logData;

    public $tries = 5; // Max retry attempts
    public $backoff = [2, 5, 10, 20]; // Retry delays (in seconds)

    public function __construct(array $logData)
    {
        $this->logData = $logData;
    }

    public function handle()
    {
        try {
            (new HttpLogSender())->send($this->logData);
        } catch (Exception $e) {
            $this->fail($e); // Mark job as failed if it repeatedly fails
        }
    }
}
