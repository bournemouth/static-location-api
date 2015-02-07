<?php

namespace BournemouthData\CoWheels;

use BournemouthData\LocationApi\LocationApiResource;
use Silex\Application;
use Silex\ServiceProviderInterface;

class CoWheelsServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
    {
    	$app['db.coWheels'] = $app->share(
		    function () {
		        $csv = array_map("str_getcsv", file(__DIR__.'/../../database/co-wheels.csv', FILE_SKIP_EMPTY_LINES));
		        $keys = array_shift($csv);

		        array_push($keys, 'id');

		        $numRows = count($csv);
		        foreach ($csv as $i => $row) {

		            $row[$numRows] = $i;
		            $csv[$i] = array_combine($keys, $row);
		        }
		        return $csv;
		    }
		);

		$app['coWheels.controller'] = $app->share(function(Application $app) {
		    return new CoWheelsController($app);
		});
    }

    public function boot(Application $app)
    {
    	$resource = new LocationApiResource(
            '/api/v1/co-wheels',
            'Co-Wheels Vehicle Locations',
            'Provides the locations of all the co-wheels car sharing vehicles in Bournemouth'
        );

        $app['locationApi.manager']->register($resource);

		$coWheelsApi = $app['controllers_factory'];
		$coWheelsApi->get('/', 'coWheels.controller:getAll');
		$coWheelsApi->get('/{id}', 'coWheels.controller:getCarLocation');
		$app->mount('/api/v1/co-wheels', $coWheelsApi);
    }
}
