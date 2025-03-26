<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->resource('banners');
$routes->group('banners', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'Banners::index');
    $routes->post('', 'Banners::create');
    $routes->put('(:num)', 'Banners::update/$1');
    $routes->delete('(:num)', 'Banners::delete/$1');
});
