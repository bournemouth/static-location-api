<?php

namespace BournemouthData\LocationApi;

use Nocarrier\Hal;
use Silex\Application;

class LocationApiManager
{
    private $apiResource;

    public function __construct(Hal $apiResource)
    {
        $this->apiResource = $apiResource;
    }

    public function register(LocationApiResource $resource)
    {
        $halResource = new Hal($resource->getUri(),
            ['name' => $resource->getName(), 'description' => $resource->getDescription()]
        );

        $this->apiResource->addResource('resources', $halResource);
    }
}
