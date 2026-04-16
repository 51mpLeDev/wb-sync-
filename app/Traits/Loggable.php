<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Loggable
{
    protected function logInfo(string $message, array $context = []): void
    {
        $this->write('info', $message, $context);
    }

    protected function logWarning(string $message, array $context = []): void
    {
        $this->write('warning', $message, $context);
    }

    protected function logError(string $message, array $context = []): void
    {
        $this->write('error', $message, $context);
    }

    private function write(string $level, string $message, array $context = []): void
    {
        Log::$level($message, $context);

        $time = now()->format('Y-m-d H:i:s');

        $contextString = !empty($context)
            ? json_encode($context, JSON_UNESCAPED_UNICODE)
            : '';

        echo "[{$time}] {$level}: {$message} {$contextString}" . PHP_EOL;
    }
}
