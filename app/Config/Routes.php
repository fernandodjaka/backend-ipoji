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

$routes->post('user/create', 'UserController::create');
$routes->post('login', 'LoginController::index');
$routes->get('getUserData', 'LoginController::getUserData', ['filter' => 'role:admin,penjual,user']);


// $routes->group('admin', ['filter' => 'role:admin'], function($routes) {
//     $routes->get('dashboard', 'AdminController::dashboard');
//     // Tambahkan route admin lainnya di sini
// });

// $routes->group('seller', ['filter' => 'role:penjual'], function($routes) {
//     $routes->get('dashboard', 'SellerController::dashboard');
//     // Tambahkan route penjual lainnya di sini
// });

// $routes->group('user', ['filter' => 'role:user,admin,penjual'], function($routes) {
//     $routes->get('profile', 'UserController::profile');
//     // Tambahkan route user lainnya di sini
// });



$routes->post('logout', 'UserController::logout');

// app/Config/Routes.php

// app/Config/Routes.php

// $routes->group('cart', ['namespace' => 'App\Controllers'], function ($routes) {
//     $routes->post('add', 'CartController::add');
// });

// //Cek Ongkir
// $routes->get('api/province', 'RajaOngkirProxy::getProvince');
// $routes->get('api/city', 'RajaOngkirProxy::getCity');
// $routes->post('api/cost', 'RajaOngkirProxy::getCost');
// $routes->get('api/ongkir/getKota', 'Ongkir::getKota');
// $routes->post('api/ongkir/cekOngkir', 'Ongkir::cekOngkir');




$routes->resource('artikel', ['controller' => 'ArtikelController']);
$routes->get('artikel/(:segment)', 'ArtikelController::show/$1');
$routes->match(['post', 'options'], 'api/artikel', 'ArtikelController::create',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'delete/artikel/(:segment)', 'ArtikelController::delete/$1',['filter' => 'cors', 'authFilter']);
$routes->post('update/artikel/(:num)', 'ArtikelController::update/$1',['filter' => 'cors', 'authFilter']);


// //KERANJANG
// $routes->get('keranjang/(:num)', 'KeranjangController::index/$1');
// $routes->match(['post', 'options'], 'keranjang/tambah-ke-keranjang/(:num)', 'ProdukController::tambahKeKeranjang/$1', ['filter' => 'cors', 'authFilter']);
// $routes->get('api/keranjang/(:num)', 'KeranjangController::getCartItemsByProductId/$1'); // Rute untuk mengambil item keranjang berdasarkan id_produk
// $routes->post('keranjang/tambah-ke-keranjang/(:num)', 'ProdukController::tambahKeKeranjang/$1');

// CART COBA
$routes->get('cartcoba/(:num)', 'CartControllerCoba::getCart/$1');
$routes->match(['post', 'options'], 'cartcoba/add', 'CartControllerCoba::add',['filter' => 'cors', 'authFilter']);
$routes->match(['post', 'options'], 'cartcoba/updateQuantity/(:num)', 'CartControllerCoba::updateQuantity/$1',['filter' => 'cors', 'authFilter']);
$routes->match(['delete', 'options'], 'cartcoba/removeItem/(:num)', 'CartControllerCoba::removeItem/$1',['filter' => 'cors', 'authFilter']);


// // RAJA ONGKIR
// $routes->get('/provinces', 'RajaOngkirController::getProvinces');
// $routes->get('/cities/(:num)', 'RajaOngkirController::getCities/$1');
// $routes->post('/shipping-cost', 'RajaOngkirController::getShippingCost');

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


//alamat terbaru

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

