<?php

/**
 * Algérie Poste MVC - Front Controller
 *
 * All requests are routed through this file.
 */

define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

// Autoloader
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = ROOT_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Bootstrap the application
$app = new \App\Core\App();

// Define routes
$app->get('/',              'HomeController',      'index');
$app->get('/bureau',        'BureauController',    'index');
$app->get('/operations',    'OperationController', 'index');
$app->get('/carnet',        'CarnetController',    'index');
$app->get('/cnep',          'CnepController',      'index');
$app->get('/trafic/detail', 'TraficController',    'detail');
$app->get('/trafic/global', 'TraficController',    'global');
$app->get('/data/import',   'DataController',      'import');
$app->post('/data/process', 'DataController',      'process');

// Run
$app->run();
