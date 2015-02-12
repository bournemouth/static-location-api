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
        $this->apiResource->addLink(
            'resource',
            $resource->getUri(),
            ['id' => $resource->getId(), 'name' => $resource->getName(), 'description' => $resource->getDescription(),]
        );
    }
}
