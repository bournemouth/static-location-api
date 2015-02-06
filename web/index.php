<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application;

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
        return new RedirectResponse('/api');
    }
);

$app->get(
    '/api',
    function () {
        $apiResource = new \Nocarrier\Hal();
        $taxiRankResource = new \Nocarrier\Hal(
            '/api/taxi-ranks',
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

$app->get(
    'api/taxi-ranks',
    function (Silex\Application $app) {

        $taxiRankRecords = $app['db.taxiRanks'];

        $taxiRankCollection = new \Nocarrier\Hal('/api/taxi-ranks');

        foreach ($taxiRankRecords as $taxiRank) {
            $taxiRankResource = new \Nocarrier\Hal('/api/taxi-ranks/' . $taxiRank['id'], $taxiRank);
            $taxiRankCollection->addResource('taxiRanks', $taxiRankResource);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankCollection->asJson(true));
        return $response;
    }
);

$app->get(
    'api/taxi-ranks/{id}',
    function (Silex\Application $app) {

        $id = $app['request']->get('id');
        $taxiRank = $app['db.taxiRanks'][$id];

        $taxiRankResource = new \Nocarrier\Hal('/api/taxi-ranks/' . $taxiRank['id'], $taxiRank);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankResource->asJson(true));
        return $response;
    }
);

$app->run();
