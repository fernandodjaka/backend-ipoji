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


//API PRODUK
$routes->resource('produk', ['controller' => 'ProdukController']);
$routes->get('produk/(:segment)', 'ProdukController::show/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'api/produk', 'ProdukController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/produk/(:segment)', 'ProdukController::delete/$1',['filter' => 'cors', 'authFilter']);
$routes->post('update/produk/(:num)', 'ProdukController::update/$1',['filter' => 'cors', 'authFilter']);


//login user
$routes->post('user/create', 'UserController::create');
$routes->match(['post', 'options'], 'login', 'LoginController::index',['filter' => 'cors', 'authFilter']);
$routes->get('getUserData', 'LoginController::getUserData', ['filter' => 'role:admin,penjual,user']);



$routes->post('logout', 'UserController::logout');



// Menambahkan artikel
$routes->resource('artikel', ['controller' => 'ArtikelController']);
$routes->get('artikel/(:segment)', 'ArtikelController::show/$1');
$routes->match(['post', 'options'], 'api/artikel', 'ArtikelController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/artikel/(:segment)', 'ArtikelController::delete/$1',['filter' => 'cors', 'authFilter']);
$routes->post('update/artikel/(:num)', 'ArtikelController::update/$1',['filter' => 'cors', 'authFilter']);



// CART COBA
$routes->get('cartcoba/(:num)', 'CartControllerCoba::getCart/$1');
$routes->match(['post', 'options'], 'cartcoba/add', 'CartControllerCoba::add',['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'cartcoba/updateQuantity/(:num)', 'CartControllerCoba::updateQuantity/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'cartcoba/removeItem/(:num)', 'CartControllerCoba::removeItem/$1',['filter' => 'cors', 'authFilter']);


//DATA WILAYAH ALAMAT

$routes->get('/api/provinces', 'WilayahController::getProvinces');
$routes->get('/api/regencies/(:num)', 'WilayahController::getRegencies/$1');
$routes->get('/api/districts/(:num)', 'WilayahController::getDistricts/$1');
$routes->get('/api/villages/(:num)', 'WilayahController::getVillages/$1');
$routes->get('/api/province/(:num)', 'WilayahController::getProvinceById/$1');
$routes->get('/api/regency/(:num)', 'WilayahController::getRegencyById/$1');
$routes->get('/api/district/(:num)', 'WilayahController::getDistrictById/$1');
$routes->get('/api/village/(:num)', 'WilayahController::getVillageById/$1');

$routes->get('transaction/show/(:num)', 'TransactionController::getTransaction/$1');
$routes->get('notifications/(:num)', 'TransactionController::getNotifications/$1');
$routes->post('transaction', 'TransactionController::create');
$routes->post('transaction/update-status/(:num)', 'TransactionController::updateStatus/$1');


//Order terbaru
$routes->match(['post', 'options'], 'orders', 'OrderController::create', ['filter' => 'cors', 'authFilter']);
$routes->get('orders/user/(:num)', 'OrderController::getOrdersByUser/$1');
$routes->get('orders/(:num)', 'OrderController::getOrder/$1');
$routes->get('orders', 'OrderController::index'); // Untuk mendapatkan semua pesanan
$routes->patch('orders/(:num)/status', 'OrderController::updateStatus/$1'); // Untuk mengubah status pesanan



// $routes->get('address/(:num)', 'AddressController::show/$1');
// $routes->post('address', 'AddressController::create');
// $routes->put('address/(:num)', 'AddressController::update/$1');
// $routes->delete('address/(:num)', 'AddressController::delete/$1');
$routes->group('address', ['filter' => 'cors'], function($routes) {
    $routes->get('user/(:num)', 'AddressController::getAddressesByUser/$1');
    $routes->get('primary/(:num)', 'AddressController::getPrimaryAddress/$1');
    $routes->match(['post', 'options'], 'create', 'AddressController::create', ['filter' => 'cors', 'authFilter']);
    $routes->match(['put', 'patch', 'options'], 'update/(:num)', 'AddressController::update/$1', ['filter' => 'cors', 'authFilter']);
    $routes->match(['delete', 'options'], 'delete/(:num)', 'AddressController::delete/$1', ['filter' => 'cors', 'authFilter']);
    $routes->match(['put', 'patch', 'options'], 'set-primary/(:num)/(:num)', 'AddressController::setPrimary/$1/$2', ['filter' => 'cors', 'authFilter']);
    // $routes->put('set-primary/(:num)/(:num)', 'AddressController::setPrimary/$1/$2', ['filter' => 'cors', 'authFilter']);
});

// $routes->group('api', function($routes) {
//     $routes->post('address', 'AddressController::create');
//     $routes->get('address', 'AddressController::index');
//     $routes->get('address/(:num)', 'AddressController::show/$1');
//     $routes->put('address/(:num)', 'AddressController::update/$1');
//     $routes->delete('address/(:num)', 'AddressController::delete/$1');
// });



// $routes->post('cartcoba/add', 'CartControllerCoba::add');
// $routes->post('cartcoba/updateQuantity/(:num)', 'CartControllerCoba::updateQuantity/$1');
// $routes->delete('cartcoba/removeItem/(:num)', 'CartControllerCoba::removeItem/$1');
// $routes->get('cartcoba/(:num)', 'CartControllerCoba::getCart/$1');

