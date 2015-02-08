<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application;
$app['debug'] = getenv('debug');

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new BournemouthData\LocationApi\LocationApiServiceProvider());
$app->register(new BournemouthData\TaxiRank\TaxiRankServiceProvider());
$app->register(new BournemouthData\CoWheels\CoWheelsServiceProvider());

return $app;
