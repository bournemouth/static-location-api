<?php

namespace BournemouthData;

use BournemouthData\TransportRepositoryInterface;
use Silex\Application;

class TransportRepository implements TransportRepositoryInterface
{
    const DATA_FILE = 'database/taxi-ranks.csv';
    /**
     * @param Application $app
     */
    public function __construct(Application $app, $fileLocation)
    {
        $this->app = $app;
        $this->fileLocation = $fileLocation;
    }

    public function fetchAll()
    {
        return $this->app['csvParser']($this->fileLocation);
    }

    public function fetchById($id)
    {
        $taxiRanks = $this->app['csvParser']($this->fileLocation);
        return $taxiRanks[$id];
    }

} 