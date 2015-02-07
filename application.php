<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application;
$app['debug'] = getenv('debug');

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new BournemouthData\TaxiRank\TaxiRankServiceProvider());
$app->register(new BournemouthData\CoWheels\CoWheelsServiceProvider());

$app->get(
    '/',
    function () {
        return new RedirectResponse('/api/v1');
    }
);

$app->get(
    '/api',
    function () {
        return new RedirectResponse('/api/v1');
    }
);

$app->get(
    '/api/v1',
    function () {
        $apiResource = new \Nocarrier\Hal();
        $taxiRankResource = new \Nocarrier\Hal(
            '/api/v1/taxi-ranks',
            [
                'name' => 'Bournemouth Taxi Rank Locations',
                'description' => 'Provides the locations of all the taxi ranks in Bournemouth'
            ]
        );
        $apiResource->addResource('resources', $taxiRankResource);

        $coWheelsResource = new \Nocarrier\Hal(
            '/api/v1/co-wheels',
            [
                'name' => 'Co-Wheels Vehicle Locations',
                'description' => 'Provides the locations of all the co-wheels car sharing vehicles in Bournemouth'
            ]
        );
        $apiResource->addResource('resources', $coWheelsResource);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($apiResource->asJson(true));
        return $response;
    }
);

return $app;
