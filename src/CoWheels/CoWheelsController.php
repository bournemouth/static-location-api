<?php

namespace BournemouthData\CoWheels;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class CoWheelsController {

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function getAll()
    {
        $taxiRankRecords = $this->app['db.coWheels'];

        $taxiRankCollection = new \Nocarrier\Hal('/api/v1/co-wheels');

        foreach ($taxiRankRecords as $taxiRank) {
            $taxiRankResource = new \Nocarrier\Hal('/api/v1/co-wheels/' . $taxiRank['id'], $taxiRank);
            $taxiRankCollection->addResource('coWheels', $taxiRankResource);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankCollection->asJson(true));
        return $response;
    }

    public function getCarLocation()
    {
        $id = $this->app['request']->get('id');
        $taxiRank = $this->app['db.coWheels'][$id];

        $taxiRankResource = new \Nocarrier\Hal('/api/v1/co-wheels/' . $taxiRank['id'], $taxiRank);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankResource->asJson(true));
        return $response;
    }

}
