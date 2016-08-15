<?php

$setting['displayErrorDetails'] = filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN);
$setting['addContentLengthHeader'] = false;
$setting['routerCacheFile'] = filter_var($_SERVER['APP_CACHE'], FILTER_VALIDATE_BOOLEAN);
$setting['determineRouteBeforeAppMiddleware'] = true;

$app = new \Slim\App(["settings" => $setting]);
