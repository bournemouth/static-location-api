<?php

namespace BournemouthData\LocationApi;

use Silex\Application;
use Silex\ServiceProviderInterface;

class LocationApiServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    	$app['locationApi.resource'] = $app->share(function () {
    		return new \Nocarrier\Hal();
    	});
    }

    public function boot(Application $app)
    {
		$app->get(
		    '/',
		    function () {
		        return new \Symfony\Component\HttpFoundation\RedirectResponse('/api/v1');
		    }
		);

		$app->get(
		    '/api',
		    function () {
		        return new \Symfony\Component\HttpFoundation\RedirectResponse('/api/v1');
		    }
		);

		$app->get(
		    '/api/v1',
		    function (Application $app) {
		        
		        $taxiRankResource = new \Nocarrier\Hal(
		            '/api/v1/taxi-ranks',
		            [
		                'name' => 'Bournemouth Taxi Rank Locations',
		                'description' => 'Provides the locations of all the taxi ranks in Bournemouth'
		            ]
		        );
		        $app['locationApi.resource']->addResource('resources', $taxiRankResource);

		        $coWheelsResource = new \Nocarrier\Hal(
		            '/api/v1/co-wheels',
		            [
		                'name' => 'Co-Wheels Vehicle Locations',
		                'description' => 'Provides the locations of all the co-wheels car sharing vehicles in Bournemouth'
		            ]
		        );
		        $app['locationApi.resource']->addResource('resources', $coWheelsResource);

		        $response = new \Symfony\Component\HttpFoundation\Response();
		        $response->headers->set('Content-Type', 'application/hal+json');
		        $response->setContent($app['locationApi.resource']->asJson(true));
		        return $response;
		    }
		);

    }
}
