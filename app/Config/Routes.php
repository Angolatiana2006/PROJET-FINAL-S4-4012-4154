<?php

namespace Config;

$routes = Services::routes();

// ============================================
// REDIRECTION RACINE VERS DASHBOARD ADMIN
// ============================================
$routes->get('/', function() {
    return redirect()->to('/client/login');
});

// ============================================
// ROUTES D'AUTHENTIFICATION
// ============================================
$routes->get('login', 'AuthController::login');
$routes->post('auth/do-login', 'AuthController::doLogin');
$routes->get('auth/logout', 'AuthController::logout');



// ============================================
// ROUTES ADMIN - PREFIXES
// ============================================
$routes->group('admin/prefixes', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'PrefixController::index');
    $routes->get('create', 'PrefixController::create');
    $routes->post('store', 'PrefixController::store');
    $routes->get('edit/(:num)', 'PrefixController::edit/$1');
    $routes->post('update/(:num)', 'PrefixController::update/$1');
    $routes->delete('delete/(:num)', 'PrefixController::delete/$1');
    $routes->post('toggle/(:num)', 'PrefixController::toggle/$1');
    $routes->get('check', 'PrefixController::checkPrefix');
});

// ============================================
// ROUTES ADMIN - BARÈMES DE FRAIS
// ============================================
$routes->group('admin/fees', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'FeeController::index');
    $routes->get('create', 'FeeController::create');
    $routes->post('store', 'FeeController::store');
    $routes->get('edit/(:num)', 'FeeController::edit/$1');
    $routes->post('update/(:num)', 'FeeController::update/$1');
    $routes->delete('delete/(:num)', 'FeeController::delete/$1');
    $routes->post('toggle/(:num)', 'FeeController::toggle/$1');
    $routes->get('calculate', 'FeeController::calculateFee');
});

// ============================================
// ROUTES ADMIN - DASHBOARD
// ============================================
$routes->group('admin/dashboard', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('export', 'DashboardController::export');
});

// Redirection admin
$routes->get('admin', function() {
    return redirect()->to('/admin/dashboard');
});

// ============================================
// ROUTES ADMIN - CLIENTS
// ============================================
$routes->group('admin/clients', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'ClientsController::index');
    $routes->get('show/(:num)', 'ClientsController::show/$1');
    $routes->get('create', 'ClientsController::create');
    $routes->post('store', 'ClientsController::store');
    $routes->get('edit/(:num)', 'ClientsController::edit/$1');
    $routes->post('update/(:num)', 'ClientsController::update/$1');
    $routes->delete('delete/(:num)', 'ClientsController::delete/$1');
    $routes->post('toggle/(:num)', 'ClientsController::toggle/$1');
    $routes->get('export', 'ClientsController::export');
    $routes->get('search', 'ClientsController::search');
});

// ============================================
// ROUTES API
// ============================================
$routes->group('api', function($routes) {
    $routes->get('prefixes', 'Admin\PrefixController::index');
    $routes->get('prefixes/check', 'Admin\PrefixController::checkPrefix');
    $routes->get('fees', 'Admin\FeeController::index');
    $routes->get('fees/calculate', 'Admin\FeeController::calculateFee');
});

// ============================================
// ROUTES CLIENT
// ============================================
$routes->get('client/login', 'ClientController::login');
$routes->post('client/do-login', 'ClientController::doLogin');
$routes->get('client/dashboard', 'ClientController::dashboard');
$routes->get('client/deposit', 'ClientController::deposit');
$routes->post('client/do-deposit', 'ClientController::doDeposit');
$routes->get('client/withdrawal', 'ClientController::withdrawal');
$routes->post('client/do-withdrawal', 'ClientController::doWithdrawal');
$routes->get('client/transfer', 'ClientController::transfer');
$routes->post('client/do-transfer', 'ClientController::doTransfer');
$routes->get('client/history', 'ClientController::history');
$routes->get('client/logout', 'ClientController::logout');
$routes->get('client/debug', 'ClientController::debugTransactions');

// ============================================
// ROUTES ADMIN - OPÉRATEURS EXTERNES
// ============================================
$routes->group('admin/external-operators', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'ExternalOperatorController::index');
    $routes->get('create', 'ExternalOperatorController::create');
    $routes->post('store', 'ExternalOperatorController::store');
    $routes->get('edit/(:num)', 'ExternalOperatorController::edit/$1');
    $routes->post('update/(:num)', 'ExternalOperatorController::update/$1');
    $routes->delete('delete/(:num)', 'ExternalOperatorController::delete/$1');
    $routes->post('toggle/(:num)', 'ExternalOperatorController::toggle/$1');
});