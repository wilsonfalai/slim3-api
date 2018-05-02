<?php

// Config
require __DIR__ . '/config.php';

// App
require __DIR__ . '/app.php';

// Dependencies
require __DIR__ . '/dependencies.php';

// Routes
require __DIR__ . '/routes.php';

// Middleware
require __DIR__ . '/middleware.php';

// Run
$app->run();