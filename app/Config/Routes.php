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

// --------------------------------------------------------------------
// RUTAS PÚBLICAS (ACCESIBLES SIN LOGIN)
// --------------------------------------------------------------------
// Login y Logout en la raíz para evitar el 404
// Rutas públicas
$routes->get('/', 'Login::index'); 
$routes->post('autenticar', 'Login::autenticar'); 
$routes->get('logout', 'Login::logout'); // <--- La ruta correcta es /logout

// --------------------------------------------------------------------------
// --- Rutas del Panel de Usuario (Tienda y Carrito) ---
// --------------------------------------------------------------------------
// El 'namespace' apunta a App\Controllers\Userview\
$routes->group('usuario', ['filter' => 'auth', 'namespace' => 'App\Controllers\Userview'], function($routes) {
    
    // Vistas principales (UsuariosController)
    $routes->get('/', 'UsuariosController::index'); 
    $routes->get('membresias', 'UsuariosController::membresias'); 
    
    // Rutas para el Carrito (CarritoController - Lógica AJAX)
    $routes->post('carrito/agregar', 'CarritoController::agregar');
    $routes->post('carrito/actualizar', 'CarritoController::actualizar'); // Aseguramos que esta ruta exista
    $routes->get('carrito/obtener', 'CarritoController::obtener');
    $routes->get('carrito/vaciar', 'CarritoController::vaciar');
    $routes->post('carrito/checkout', 'CarritoController::checkout');
    
    // Rutas para el Filtro de Discos (AJAX)
    $routes->get('ajax/discos', 'UsuariosController::getDiscosByCategory/0');
    $routes->get('ajax/discos/(:num)', 'UsuariosController::getDiscosByCategory/$1');
});


// --------------------------------------------------------------------------
// --- Rutas Administrador (con filtro admin) ---
// --------------------------------------------------------------------------
$routes->group('admin', ['namespace' => 'App\Controllers\Adminview', 'filter' => 'admin'], function ($routes) {
    
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

    // 4. RUTAS DE USUARIOS 
    $routes->get('usuarios', 'UsuariosController::index');
    $routes->get('usuarios/crear', 'UsuariosController::crear');
    $routes->post('usuarios/guardar', 'UsuariosController::guardar'); 
    $routes->get('usuarios/editar/(:num)', 'UsuariosController::editar/$1');
    $routes->post('usuarios/actualizar/(:num)', 'UsuariosController::actualizar/$1');
    $routes->post('usuarios/eliminar/(:num)', 'UsuariosController::eliminar/$1');

    // 5. CRUD MEMBRESÍAS (Llama a MembresiaController)
    $routes->get('membresias', 'MembresiaController::index'); 
    $routes->match(['get', 'post'], 'membresias/crear', 'MembresiaController::crearMembresia');
    $routes->match(['get', 'post'], 'membresias/editar/(:num)', 'MembresiaController::editarMembresia/$1');
    $routes->get('membresias/eliminar/(:num)', 'MembresiaController::eliminarMembresia/$1');
    $routes->get('membresias/usuarios/(:num)', 'MembresiaController::usuarios/$1');


    // 6. RUTAS QUE SE MANTIENEN EN Admin.php (Ejemplos)
    $routes->get('pagos', 'Admin::pagos');
    $routes->match(['get', 'post'], 'pagos/crear', 'Admin::crearPago');
    $routes->match(['get', 'post'], 'pagos/editar/(:num)', 'Admin::editarPago/$1');
    $routes->get('pagos/eliminar/(:num)', 'Admin::eliminarPago/$1');
    $routes->get('ingresos', 'Admin::ingresos');
    $routes->match(['get', 'post'], 'ingresos/crear', 'Admin::crearIngreso');

    // 7. RUTAS PARA PEDIDOS (Llaman a PedidoController)
    $routes->get('pedidos', 'PedidoController::index');
    $routes->get('pedidos/detalle/(:num)', 'PedidoController::verDetalle/$1');
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