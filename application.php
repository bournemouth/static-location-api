<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application;
$app['debug'] = true;
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['csvParser'] = $app->protect(
    function ($csvFile) {
        $addIds = false;

        $csv = array_map("str_getcsv", file($csvFile, FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);

        if (!array_key_exists('id', $keys)){
            $addIds = true;
            array_push($keys, 'id');
        }

        $numRows = count($csv);

        foreach ($csv as $i => $row) {

            if ($addIds){
                $row[$numRows] = $i;
            }

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
    function () use ($app){
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
    return new BournemouthData\TaxiRank\TaxiRankController(
        $app,
        new BournemouthData\TaxiRank\TaxiRankRepository($app, 'database/taxi-ranks.csv'),
        'taxiRank',
        'api/v1/taxi-ranks/'
    );
});

$taxiApi = $app['controllers_factory'];
$taxiApi->get('/', 'taxiRank.controller:getAll')->bind('taxiRanks');
$taxiApi->get('/{id}', 'taxiRank.controller:getOne')->bind('taxiRank');
$app->mount('api/v1/taxi-ranks', $taxiApi);

return $app;
