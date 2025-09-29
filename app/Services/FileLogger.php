<?php

namespace App\Services;

class FileLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        file_put_contents(storage_path('logs/custom.log'), $message.PHP_EOL, FILE_APPEND);
    }
}
