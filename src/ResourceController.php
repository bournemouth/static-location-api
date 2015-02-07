<?php

namespace BournemouthData;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;


abstract class ResourceController {

    /* @var TransportRepositoryInterface $repository */
    protected $repository;

    protected $baseRoute;

    protected $collectionName;

    /**
     * @param Application $app
     * @param TransportRepositoryInterface $repository
     * @param $collectionName
     * @param $baseRoute
     */
    public function __construct(Application $app, TransportRepositoryInterface $repository, $collectionName, $baseRoute)
    {
        $this->app = $app;
        $this->repository = $repository;
        $this->collectionName = $collectionName;
        $this->baseRoute = $baseRoute;
    }

    public function getAll()
    {
        $records = $this->repository->fetchAll();
        $collection = new \Nocarrier\Hal($this->baseRoute);

        foreach ($records as $item) {

            $resource = new \Nocarrier\Hal($this->baseRoute . $item['id'], $item);
            $collection->addResource($this->collectionName, $resource);
        }

        return $this->ResourceToHalJsonResponse($collection);
    }

    public function getOne($id)
    {
        $record = $this->repository->fetchById($id);
        $resource = new \Nocarrier\Hal($this->baseRoute . $record['id'], $record);

        return $this->ResourceToHalJsonResponse($resource);
    }

    protected function ResourceToHalJsonResponse($resource)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($resource->asJson(true));
        return $response;
    }

} 