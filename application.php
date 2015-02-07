<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application;
$app['debug'] = true;
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['db.taxiRanks'] = $app->share(
    function () {
        $csv = array_map("str_getcsv", file(__DIR__.'/database/taxi-ranks.csv', FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

            array_push($keys, 'id');

        $numRows = count($csv);
        foreach ($csv as $i => $row) {

            $row[$numRows] = $i;
            $csv[$i] = array_combine($keys, $row);
        }

        foreach ($csv as &$c) {
            $c['lat'] = (double) $c['lat'];
            $c['lat'] = (double) $c['lat'];
        }


        return $csv;
    }
);

$app['db.coWheels'] = $app->share(
    function () {
        $csv = array_map("str_getcsv", file(__DIR__.'/database/co-wheels.csv', FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        array_push($keys, 'id');

        $numRows = count($csv);
        foreach ($csv as $i => $row) {

            $row[$numRows] = $i;
            $csv[$i] = array_combine($keys, $row);
        }
        return $csv;
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


$app['taxiRank.controller'] = $app->share(function(Silex\Application $app) {
    return new BournemouthData\TaxiRank\TaxiRankController($app);
});

$taxiApi = $app['controllers_factory'];
$taxiApi->get('/', 'taxiRank.controller:getAll')->bind('taxiRanks');
$taxiApi->get('/{id}', 'taxiRank.controller:getTaxiRank')->bind('taxiRank');
$app->mount('/api/v1/taxi-ranks', $taxiApi);

$app['coWheels.controller'] = $app->share(function(Silex\Application $app) {
    return new BournemouthData\CoWheels\CoWheelsController($app);
});

$coWheelsApi = $app['controllers_factory'];
$coWheelsApi = $app['controllers_factory'];
$coWheelsApi->get('/', 'coWheels.controller:getAll');
$coWheelsApi->get('/{id}', 'coWheels.controller:getCarLocation');
$app->mount('/api/v1/co-wheels', $coWheelsApi);


return $app;
