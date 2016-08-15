<?php

// Access-Control-Allow-Origin: *

/**
 * Set app timezone
 */
date_default_timezone_set('UTC');

/**
 * Detect if .env exists and load required specific environment variables
 */
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    $dotenv->required([
        'APP_ENV',
        'APP_DEBUG',
        'APP_MAINT',
        'APP_CACHE',
        'APP_PASS_ALGO_CONST',
        'APP_SECRET',
        'APP_PROTOCOL',
        'APP_DOMAIN',

        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASS',

        'SMTP_SERVER',
        'SMTP_PORT',
        'SMTP_USER',
        'SMTP_PASS',

        'CLIENT_NAME',
        'CLIENT_URL'
    ]);
} else {
    //
}

/**
 * Turn on maintenance mode by setting API_MAINT to true
 */
if (filter_var($_SERVER['APP_MAINT'], FILTER_VALIDATE_BOOLEAN)) {
    die('&#9760; Down for a quick maintenance. Check back shortly. &#9760;');
}
