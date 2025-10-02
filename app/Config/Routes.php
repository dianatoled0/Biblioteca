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
$routes->setAutoRoute(false);

/*
|--------------------------------------------------------------------------
| Rutas públicas (Login / Usuario)
|--------------------------------------------------------------------------
*/
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('login/salir', 'Login::salir');

// Panel usuario (requiere login)
$routes->get('panel', 'Login::panel', ['filter' => 'auth']);

/*
|--------------------------------------------------------------------------
| Rutas de Administrador (agrupadas)
|--------------------------------------------------------------------------
*/
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'admin'], function($routes) {

    // Dashboard
    $routes->get('/', 'Admin::index');

    // Categorías
    $routes->get('categorias', 'CategoriaController::index');
    $routes->get('categorias/crear', 'CategoriaController::crear');
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/editar/(:num)', 'CategoriaController::editar/$1');
    $routes->post('categorias/actualizar/(:num)', 'CategoriaController::actualizar/$1');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');

    // Discos
    $routes->get('discos', 'Admin::discos');
    $routes->match(['get','post'], 'discos/crear', 'Admin::crearDisco');
    $routes->match(['get','post'], 'discos/editar/(:num)', 'Admin::editarDisco/$1');
    $routes->get('discos/eliminar/(:num)', 'Admin::eliminarDisco/$1');

    // Usuarios
    $routes->get('usuarios', 'Admin::usuarios');
    $routes->match(['get','post'], 'usuarios/crear', 'Admin::crearUsuario');
    $routes->match(['get','post'], 'usuarios/editar/(:num)', 'Admin::editarUsuario/$1');
    $routes->get('usuarios/eliminar/(:num)', 'Admin::eliminarUsuario/$1');

    // Membresías
    $routes->get('membresias', 'Admin::membresias');
    $routes->match(['get','post'], 'membresias/crear', 'Admin::crearMembresia');
    $routes->match(['get','post'], 'membresias/editar/(:num)', 'Admin::editarMembresia/$1');
    $routes->get('membresias/eliminar/(:num)', 'Admin::eliminarMembresia/$1');

    // Tipos de pago
    $routes->get('pagos', 'Admin::pagos');
    $routes->match(['get','post'], 'pagos/crear', 'Admin::crearPago');
    $routes->match(['get','post'], 'pagos/editar/(:num)', 'Admin::editarPago/$1');
    $routes->get('pagos/eliminar/(:num)', 'Admin::eliminarPago/$1');

    // Ingresos
    $routes->get('ingresos', 'Admin::ingresos');
    $routes->match(['get','post'], 'ingresos/crear', 'Admin::crearIngreso');

    // Recibos
    $routes->get('recibos', 'Admin::recibos');
    $routes->get('recibos/detalle/(:num)', 'Admin::detalleRecibo/$1');
});

/*
|--------------------------------------------------------------------------
| Rutas adicionales según el entorno
|--------------------------------------------------------------------------
*/
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
