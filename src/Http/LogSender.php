<?php

namespace Eloquent\LogSender\Http;

use Eloquent\LogSender\Models\FailedLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class LogSender
{
    public function send(array $logData)
    {
        $endpoint = config('customlogger.endpoint');

        try {
            $response = Http::retry(5, 2000, function ($exception, $request) {
                return $exception instanceof Exception || $request->status() === 429;
            })->post($endpoint, $logData);

            if ($response->failed()) {
                throw new Exception("Log sending failed with status: " . $response->status());
            }
        } catch (Exception $e) {
            Log::error("Rate limit hit, storing log for later retry.");
            FailedLog::create([
                'level' => $logData['level'],
                'message' => $logData['message'],
                'context' => $logData['context'],
                'environment' => $logData['environment']
            ]);
        }
    }

    public function sendBatch(array $logBatch)
    {
        $endpoint = config('customlogger.endpoint');

        try {
            $response = Http::retry(5, 2000, function ($exception, $request) {
                return $exception instanceof Exception || $request->status() === 429;
            })->post($endpoint, ['logs' => $logBatch]);

            if ($response->failed()) {
                throw new Exception("Batch log sending failed.");
            }
        } catch (Exception $e) {
            Log::error("Batch log sending failed, storing for retry.");
            foreach ($logBatch as $logData) {
                FailedLog::create([
                    'level' => $logData['level'],
                    'message' => $logData['message'],
                    'context' => $logData['context'],
                    'environment' => $logData['environment'],
                    'sent' => false,
                ]);
                
            }
        }
    }
}
