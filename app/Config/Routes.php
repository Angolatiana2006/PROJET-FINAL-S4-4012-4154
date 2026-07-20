<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Route pour le test SQLite
$routes->get('sqlite-test', 'SqliteTest::index');
$routes->get('test-db', 'SqliteTest::index');
