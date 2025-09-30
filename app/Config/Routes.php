<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('registro', 'Login::registro');
$routes->post('login/crear', 'Login::crear');
$routes->get('panel', 'Login::panel');
$routes->get('admin', 'Login::admin');   // 👈 nueva ruta
$routes->get('login/salir', 'Login::salir');
