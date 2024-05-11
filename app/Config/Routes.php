<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('user', ['controller' => 'userController']);
$routes->match(['post', 'options'], 'api/registertoko', 'TokoController::create');
$routes->match(['post', 'options'], 'api/register', 'userController::create');
$routes->match(['put', 'options'], 'update/user/(:segment)','userController::update/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/user/(:segment)','userController::deleteUser/$1', ['filter' => 'cors', 'authFilter']);
$routes->get('api/total-users', 'userController::totalUsers',['filter' => 'cors', 'authFilter']);

$routes->resource('produk', ['controller' => 'ProdukController']);
$routes->get('produk/(:segment)', 'ProdukController::show/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'api/produk', 'ProdukController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/produk/(:segment)', 'ProdukController::delete/$1',['filter' => 'cors', 'authFilter']);

$routes->match(['post', 'options'], 'login', 'LoginController::index', ['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'loginadmin', 'AdminController::index', ['filter' => 'cors','authFilter']);
$routes->match(['post', 'options'], 'loginpenjual', 'PenjualController::index', ['filter' => 'cors','authFilter']);


$routes->post('logout', 'UserController::logout');

// app/Config/Routes.php

// app/Config/Routes.php

$routes->group('cart', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->post('add', 'CartController::add');
});


$routes->get('api/province', 'RajaOngkirProxy::getProvince');
$routes->get('api/city', 'RajaOngkirProxy::getCity');
$routes->post('api/cost', 'RajaOngkirProxy::getCost');


$routes->resource('orders', ['controller' => 'OrdersController']);
$routes->match(['post', 'options'], 'api/order', 'OrdersController::createOrder',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/order/(:segment)', 'OrdersController::delete/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'updateStatus/(:segment)', 'OrdersController::updateStatus/$1',['filter' => 'cors', 'authFilter']);

$routes->resource('delivery', ['controller' => 'DeliveryController']);
$routes->match(['post', 'options'], 'api/delivery', 'DeliveryController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['put', 'options'], 'api/delivery/update/(:segment)', 'DeliveryController::update/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'api/delivery/delete/(:segment)', 'DeliveryController::delete/$1',['filter' => 'cors', 'authFilter']);



$routes->resource('artikel', ['controller' => 'ArtikelController']);
$routes->get('artikel/(:segment)', 'ArtikelController::show/$1');
$routes->match(['post', 'options'], 'api/artikel', 'ArtikelController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/artikel/(:segment)', 'ArtikelController::delete/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['put', 'options'], 'update/artikel/(:segment)', 'ArtikelController::update/$1',['filter' => 'cors', 'authFilter']);

$routes->get('api/ongkir/getKota', 'Ongkir::getKota');
$routes->post('api/ongkir/cekOngkir', 'Ongkir::cekOngkir');

//KERANJANG
$routes->get('user/(:num)', 'KeranjangController::index/$1');
$routes->match(['post', 'options'], 'keranjang/tambah-ke-keranjang/(:num)', 'ProdukController::tambahKeKeranjang/$1', ['filter' => 'cors', 'authFilter']);
