<?php

// Config
require __DIR__ . '/config.php';

// App
require __DIR__ . '/app.php';

// Dependencies
require __DIR__ . '/dependencies.php';

// Middleware
require __DIR__ . '/middleware.php';

// Routes
require __DIR__ . '/routes.php';

// Run
$app->run();