<?php

use App\Services\UrlParser;
use Illuminate\Support\Facades\Route;
use App\Support\Container;
use App\Services\LoggerInterface;
use App\Services\FileLogger;

Route::get('/di-test', function () {
    $container = new Container();
    $container->singleton(LoggerInterface::class, FileLogger::class);

    $logger = $container->make(LoggerInterface::class);
    $logger->log("Hello DI!");

    return "Logged!";
});

Route::get('/parse-url', function () {
    $parser = new UrlParser();

    $url = "https://user:pass@example.com:8080/path/to/page?foo=bar#section1";
    $parsed = $parser->parse($url);

    return response()->json($parsed);
});
