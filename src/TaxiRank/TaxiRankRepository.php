<?php

namespace BournemouthData\TaxiRank;

use BournemouthData\TransportRepository;
use Silex\Application;

class TaxiRankRepository extends TransportRepository
{

    /**
     * @param Application $app
     */
    public function __construct(Application $app, $fileLocation)
    {
        parent::__construct($app, $fileLocation);
    }

} 