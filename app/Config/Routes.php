<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

/** @var RouteCollection $routes */
$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Rutas públicas
$routes->get('/', 'Login::index');
$routes->post('login/autenticar', 'Login::autenticar');
$routes->get('login/logout', 'Login::logout');

// Rutas usuario (con filtro auth)
$routes->group('usuario', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Usuario::index'); // controlador Usuario.php
});

// Rutas administrador (con filtro admin)
$routes->group('admin', ['namespace' => 'App\Controllers\Adminview', 'filter' => 'admin'], function($routes) {
    // 1. DASHBOARD
    $routes->get('/', 'Admin::index'); 

    // 2. RUTAS PARA CATEGORÍAS (Llaman a CategoriaController)
    $routes->get('categorias', 'CategoriaController::index');
    $routes->get('categorias/crear', 'CategoriaController::crear');
    $routes->post('categorias/guardar', 'CategoriaController::guardar');
    $routes->get('categorias/editar/(:num)', 'CategoriaController::editar/$1');
    $routes->post('categorias/actualizar/(:num)', 'CategoriaController::actualizar/$1');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::eliminar/$1');

    // 3. CRUD DISCOS (Llaman a DiscoController)
    $routes->get('discos', 'DiscoController::index');
    $routes->get('discos/crear', 'DiscoController::crear');
    $routes->post('discos/guardar', 'DiscoController::guardar');
    $routes->get('discos/editar/(:num)', 'DiscoController::editar/$1');
    $routes->post('discos/actualizar/(:num)', 'DiscoController::actualizar/$1');
    $routes->get('discos/eliminar/(:num)', 'DiscoController::eliminar/$1');


    // 4. RUTAS DE USUARIOS (Con corrección GET/POST explícita)
    $routes->get('usuarios', 'UsuariosController::index');
    
    // Crear Usuario
    $routes->get('usuarios/crear', 'UsuariosController::crear'); 
    $routes->post('usuarios/crear', 'UsuariosController::crear'); 
    
    // Editar Usuario
    $routes->get('usuarios/editar/(:num)', 'UsuariosController::editar/$1');
    $routes->post('usuarios/editar/(:num)', 'UsuariosController::editar/$1');
    
    $routes->get('usuarios/eliminar/(:num)', 'UsuariosController::eliminar/$1');


    // 5. RUTAS QUE SE MANTIENEN EN Admin.php
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