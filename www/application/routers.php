<?php

use Phalcon\Mvc\Router;

/* ==================================================
 * Registering a router
 * ================================================== */

$config = $this->config;

$manager->set('router', function() use ($config) {
    
    $router = new Router();
    $router->setDefaultController($config->router->controllerDefault);
    $router->setDefaultAction($config->router->actionDefault);
    $router->removeExtraSlashes(TRUE);
    
    $router->add('/:controller/:action/:params', [
        'controller'    => 1,
        'action'        => 2,
        'params'        => 3
    ]);

    $router->add('/:controller/:action/', [
        'controller'    => 1,
        'action'        => 2
    ]);

    $router->add('/:controller/:action', [
        'controller'    => 1,
        'action'        => 2
    ]);

    $router->add('/:controller/', [
        'controller'    => 1,
        'action'        => $config->router->actionDefault
    ]);

    $router->add('/:controller', [
        'controller'    => 1,
        'action'        => $config->router->actionDefault
    ]);

    $router->add('/', [
        'controller'    => $config->router->controllerDefault,
        'action'        => $config->router->actionDefault
    ]);
        
    $router->add('', [
        'controller'    => $config->router->controllerDefault,
        'action'        => $config->router->actionDefault
    ]);
        
    return $router;
    
});
