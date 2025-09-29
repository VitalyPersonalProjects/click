<?php

namespace App\Services;

interface LoggerInterface
{
    public function log(string $message): void;
}
