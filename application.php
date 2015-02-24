<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application;
$app['debug'] = getenv('debug');
$app['forceHttps'] = getenv('FORCE_HTTPS');

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new BournemouthData\LocationApi\LocationApiServiceProvider());
$app->register(new BournemouthData\TaxiRank\TaxiRankServiceProvider());
$app->register(new BournemouthData\CoWheels\CoWheelsServiceProvider());
$app->register(new BournemouthData\Security\SecurityServiceProvider());

return $app;
