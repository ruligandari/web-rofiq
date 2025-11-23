<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Default route - redirect to admin login
$routes->get('/', function() {
    return redirect()->to('/admin/login');
});

// Swagger API Documentation
$routes->get('api-docs', function() {
    return view('swagger/index');
});

$routes->group('admin', ['namespace' => 'App\\Controllers\\Admin'], function ($routes) {
    $routes->get('login', 'AuthController::index');
    $routes->post('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');
    
    $routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

    $routes->get('siswa', 'SiswaController::index', ['filter' => 'auth']);
    $routes->get('siswa/create', 'SiswaController::create', ['filter' => 'auth']);
    $routes->post('siswa/store', 'SiswaController::store', ['filter' => 'auth']);
    $routes->get('siswa/edit/(:num)', 'SiswaController::edit/$1', ['filter' => 'auth']);
    $routes->post('siswa/update/(:num)', 'SiswaController::update/$1', ['filter' => 'auth']);
    $routes->get('siswa/delete/(:num)', 'SiswaController::delete/$1', ['filter' => 'auth']);
    $routes->get('siswa/export', 'SiswaController::export', ['filter' => 'auth']);

    $routes->get('soal', 'SoalController::index', ['filter' => 'auth']);
    $routes->get('soal/create', 'SoalController::create', ['filter' => 'auth']);
    $routes->post('soal/store', 'SoalController::store', ['filter' => 'auth']);
    $routes->get('soal/edit/(:num)', 'SoalController::edit/$1', ['filter' => 'auth']);
    $routes->post('soal/update/(:num)', 'SoalController::update/$1', ['filter' => 'auth']);
    $routes->get('soal/delete/(:num)', 'SoalController::delete/$1', ['filter' => 'auth']);
    $routes->get('soal/import', 'SoalController::import', ['filter' => 'auth']);
    $routes->post('soal/import', 'SoalController::processImport', ['filter' => 'auth']);
    $routes->get('soal/download-template', 'SoalController::downloadTemplate', ['filter' => 'auth']);
    $routes->post('soal/settings', 'SoalController::updateSettings');

    $routes->get('nilai', 'NilaiController::index', ['filter' => 'auth']);
    $routes->get('nilai/export', 'NilaiController::export', ['filter' => 'auth']);
});

$routes->group('api', ['namespace' => 'App\\Controllers\\Api'], function($routes) {
    $routes->post('login', 'ApiController::login');
    $routes->get('soal', 'ApiController::getSoal');
    $routes->post('soal/start', 'ApiQuizController::start');
    $routes->post('soal/submit', 'ApiQuizController::submit');
    
    // Nilai - History for AR app
    $routes->get('nilai', 'ApiNilaiController::getNilai');
});

$routes->get('api-docs', function() {
    return view('swagger/index');
});
