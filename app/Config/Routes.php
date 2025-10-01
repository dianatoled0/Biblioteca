<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Cargar el sistema de rutas por defecto
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
|--------------------------------------------------------------------------
| Rutas predeterminadas
|--------------------------------------------------------------------------
*/
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // 👈 puedes poner true si prefieres autorouting

/*
|--------------------------------------------------------------------------
| Rutas personalizadas
|--------------------------------------------------------------------------
*/
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('panel', 'Login::panel');
$routes->get('admin', 'Login::admin', ['filter' => 'admin']);
$routes->get('login/salir', 'Login::salir');

/*
|--------------------------------------------------------------------------
| Rutas adicionales según el entorno
|--------------------------------------------------------------------------
*/
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

