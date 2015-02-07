<?php

namespace BournemouthData\TaxiRank;

use BournemouthData\ResourceController;
use BournemouthData\TransportRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class TaxiRankController extends ResourceController {

    /**
     * @param Application $app
     * @param TransportRepositoryInterface $repository
     * @param $collectionName
     * @param $baseRoute
     */
    public function __construct(Application $app, TransportRepositoryInterface $repository, $collectionName, $baseRoute)
    {
        parent::__construct($app, $repository, $collectionName, $baseRoute);
    }


}
