<?php

namespace Auth\Provider;

use Auth\Auth;
use Auth\Adapter\DoctrineAdapter;
use Auth\Adapter\DigestAdapter;
use Silex\Application;
use Silex\ServiceProviderInterface;

class AuthServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $session = $app['session'];
        //die(get_class($session));
        //if (!($session instanceof Symfony\Component\HttpFoundation\Session))
        //{
        //    throw new \InvalidArgumentException('$app[\'session\'] must be an instance of Symfony\Component\HttpFoundation\Session');
        //}

        $app['auth.adapter'] = $app->share(function() use ($app)
                {
                    $adapter = $app['auth.adapterName'];
                    switch ($adapter)
                    {
                        case 'DoctrineAdapter':
                            $entityManager = $app['db.orm.em'];
                            /*
                            if (!$entityManager instanceof Doctrine\ORM\EntityManager)
                            {
                                throw new \InvalidArgumentException('$app[\'db.orm.em\'] must be an instance of Doctrine\ORM\EntityManager');
                            }
                            */
                            return new DoctrineAdapter($entityManager, $app['auth.entityName'], $app['auth.column.username'], $app['auth.column.password']);
                            break;
                    }
                });

        $app['auth'] = $app->share(function() use($app)
                {
                    return new Auth($app['auth.adapter'], $app['session']);
                });
    }

}