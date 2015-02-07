<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application;
$app['debug'] = true;
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['db.taxiRanks'] = $app->share(
    function () {
        return [
            1 => [
                'id' => 1,
                'name' => 'Test',
                'lat' => 0.0,
                'lng' => 0.0
            ]
        ];
    }
);

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

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($apiResource->asJson(true));
        return $response;
    }
);


$app['taxiRank.controller'] = $app->share(function(Silex\Application $app) {
    return new BournemouthData\TaxiRank\TaxiRankController($app);
});

$taxiApi = $app['controllers_factory'];
$taxiApi->get('/', 'taxiRank.controller:getAll')->bind('taxiRanks');
$taxiApi->get('/{id}', 'taxiRank.controller:getTaxiRank')->bind('taxiRank');
$app->mount('/api/v1/taxi-ranks', $taxiApi);

return $app;
