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

        $app['locationApi.manager'] = $app->share(function (Application $app) {
            return new LocationApiManager($app['locationApi.resource']);
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
                $response = new \Symfony\Component\HttpFoundation\Response();
                $response->headers->set('Content-Type', 'application/hal+json');
                $response->setContent($app['locationApi.resource']->asJson(true));
                return $response;
            }
        );
    }
}
