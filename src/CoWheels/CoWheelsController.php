<?php

namespace BournemouthData\CoWheels;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class CoWheelsController
{

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function getAll()
    {
        /** @var CoWheelLocation[] $coWheelsRecords */
        $coWheelsRecords = $this->app['db.coWheels'];

        $coWheelsCollection = new \Nocarrier\Hal('/api/v1/co-wheels');

        foreach ($coWheelsRecords as $coWheelLocation) {
            $resource = new \Nocarrier\Hal(
                '/api/v1/co-wheels/' . $coWheelLocation->getId(), $this->serialize($coWheelLocation)
            );
            $coWheelsCollection->addResource('coWheels', $resource);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($coWheelsCollection->asJson(true));
        return $response;
    }

    public function getCarLocation()
    {
        $id = $this->app['request']->get('id');

        /** @var CoWheelLocation[] $coWheelLocation */
        $coWheelLocation = $this->app['db.coWheels'][$id];

        $taxiRankResource = new \Nocarrier\Hal(
            '/api/v1/co-wheels/' . $coWheelLocation->getId(), $this->serialize($coWheelLocation)
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankResource->asJson(true));
        return $response;
    }

    /**
     * @param CoWheelLocation $coWheelLocation
     * @return array
     */
    private function serialize(CoWheelLocation $coWheelLocation)
    {
        return [
            'id' => $coWheelLocation->getId(),
            'name' => $coWheelLocation->getName(),
            'lat' => $coWheelLocation->getLat(),
            'lng' => $coWheelLocation->getLng()
        ];
    }

}
