<?php

namespace BournemouthData\TaxiRank;

use BournemouthData\LocationApi\LocationApiResource;
use Silex\Application;
use Silex\ServiceProviderInterface;

class TaxiRankServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    	$app['db.taxiRanks'] = $app->share(
		    function () {
		        $csv = array_map("str_getcsv", file(__DIR__.'/../../database/taxi-ranks.csv', FILE_SKIP_EMPTY_LINES));
		        $keys = array_shift($csv);

		            array_push($keys, 'id');

		        $numRows = count($csv);
		        foreach ($csv as $i => $row) {

		            $row[$numRows] = $i;
		            $csv[$i] = array_combine($keys, $row);
		        }

		        foreach ($csv as &$c) {
		            $c['lat'] = (double) $c['lat'];
		            $c['lng'] = (double) $c['lng'];
		        }


		        return $csv;
		    }
		);

		$app['taxiRank.controller'] = $app->share(function(Application $app) {
		    return new TaxiRankController($app);
		});
    }

    public function boot(Application $app)
    {
    	$resource = new LocationApiResource(
            '/api/v1/taxi-ranks',
            'Bournemouth Taxi Rank Locations',
            'Provides the locations of all the taxi ranks in Bournemouth'
        );

        $app['locationApi.manager']->register($resource);

		$taxiApi = $app['controllers_factory'];
		$taxiApi->get('/', 'taxiRank.controller:getAll')->bind('taxiRanks');
		$taxiApi->get('/{id}', 'taxiRank.controller:getTaxiRank')->bind('taxiRank');
		$app->mount('/api/v1/taxi-ranks', $taxiApi);
    }
}
