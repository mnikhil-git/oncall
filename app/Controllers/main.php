<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function() use ($app)
        {
            if (null === $count = $app['session']->get('count'))
            {
                $count = 0;
            }
            $app['session']->set('count', ++$count);
            return $app['twig']->render('index.twig', array('count' => $count));
        });

$app->get('/hello/{name}', function ($name) use ($app)
        {
            return $app['twig']->render('hello.twig', array('name' => $name));
        });

$app->get('/users', function(Request $request) use ($app)
        {
            if ($app['auth']->hasIdentity())
            {
                $users = $app['db.orm.em']->getRepository('\Entity\User')->findAll();
                return $app['twig']->render('users.twig', array('users' => $users));
            }
            else
            {
                return $app->redirect($request->getBaseUrl() . '/login');
            }
        });


$app->match('/login/', function(Request $request) use ($app)
        {
            if ($request->getMethod() == 'POST')
            {
                //die(var_dump($request->get('username')));
                /** @var $adapter \Auth\Adapter\DoctrineAdapter */
                $adapter = $app['auth.adapter'];
                $adapter->setUsername($request->get('username'))->setPassword($request->get('password'));
                //die("adapter username:  " . $adapter->getUsername());
                $result = $app['auth']->authenticate();
                if ($result->isValid())
                {
                    return $app->redirect($request->getBaseUrl() . '/hello/' . $app['auth']->getIdentity());
                }
                else
                {
                    return $app['twig']->render('login.twig', array('username' => $request->get('username'), 'errors' => $result->getMessages()));
                }
            }
            else
            {
                return $app['twig']->render('login.twig', array('username' => '', 'errors' => array()));
            }
        })->method('GET|POST');

$app->get('/logout', function(Request $request) use ($app)
        {
            $app['auth']->clearIdentity();
            return $app->redirect($request->getBaseUrl() . '/');
        });