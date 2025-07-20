<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Set default namespace for all routes
$routes->setDefaultNamespace('App\Controllers');

// Test routes (outside groups for direct access)
$routes->get('test', 'TestController::index');
$routes->get('test/dbTest', 'TestController::dbTest');
$routes->get('test/phpInfo', 'TestController::phpInfo');
$routes->get('test/checkTables', 'TestController::checkTables');
$routes->get('test/testBillInsert', 'TestController::testBillInsert');
$routes->get('test/testNotifications', 'TestController::testNotifications');
$routes->get('test/debugNotifications', 'TestController::debugNotifications');
$routes->get('diagnostic/notifications', 'DiagnosticController::notificationDiagnostic');
$routes->get('database/setup', 'DatabaseController::setup');
$routes->get('database/reset', 'DatabaseController::reset');
$routes->get('database/fixSchema', 'DatabaseController::fixSchema');

// Authentication routes
$routes->group('', ['namespace' => 'App\Controllers'], function($routes) {
    
    // Default route - redirect to login
    $routes->get('/', 'AuthController::login');
    
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::loginProcess');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::registerProcess');
    $routes->get('logout', 'AuthController::logout');
});

// Admin routes
$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('users/edit/(:num)', 'AdminController::editUser/$1');
    $routes->post('users/update/(:num)', 'AdminController::updateUser/$1');
    $routes->get('users/delete/(:num)', 'AdminController::deleteUser/$1');
    $routes->get('users/toggle-status/(:num)', 'AdminController::toggleUserStatus/$1');
    $routes->get('bills', 'AdminController::bills');
    $routes->get('bills/approve/(:num)', 'AdminController::approveBill/$1');
    $routes->get('bills/reject/(:num)', 'AdminController::rejectBill/$1');
    $routes->get('bills/delete/(:num)', 'AdminController::deleteBill/$1');
    $routes->get('notifications', 'AdminController::notifications');
    $routes->get('api/notifications', 'AdminController::getNotifications');
    $routes->post('api/notifications/mark-read/(:num)', 'AdminController::markNotificationRead/$1');
});

// User routes
$routes->group('user', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('dashboard', 'UserController::dashboard');
    $routes->get('create-bill', 'UserController::createBill');
    $routes->post('store-bill', 'UserController::storeBill');
    $routes->get('bills', 'UserController::bills');
    $routes->get('bill/(:num)', 'UserController::viewBill/$1');
});
