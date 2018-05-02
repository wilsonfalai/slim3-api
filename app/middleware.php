<?php

// Application middleware

$app->add(App\Middleware\ValidationMiddleware::class);
$app->add(App\Middleware\AuthMiddleware::class);
$app->add(\App\Middleware\RouterMiddleware::class);
