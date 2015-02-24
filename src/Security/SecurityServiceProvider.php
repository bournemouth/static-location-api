<?php

namespace BournemouthData\Security;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class SecurityServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
            if ($app['forceHttps'] && $request->getScheme() === 'http') {
                return $app->redirect(
                    'https://' . $request->getHttpHost() . $request->getRequestUri()
                );
            }
        });
    }

    public function boot(Application $app)
    {
    }
}
