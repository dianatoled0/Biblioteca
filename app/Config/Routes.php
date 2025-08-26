<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('registro', 'Login::registro');
$routes->post('login/crear', 'Login::crear');
$routes->get('panel', 'Login::panel', ['filter' => 'auth']);
$routes->get('login/salir', 'Login::salir');