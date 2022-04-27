<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Controllers\LinkController;
use App\Controllers\TestController;
use App\Core\Application;
use App\Controllers\AuthController;
use Firebase\JWT\JWT;

require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ],
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'],
        'issuer' => $_ENV['JWT_ISSUER'],
        'expire' => $_ENV['JWT_EXPIRE']
    ]
];

$app = new Application(dirname(__DIR__), $config);


$app->router->post('/register', [AuthController::class, 'register']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/links', [LinkController::class, 'index']);
$app->router->post('/links/store', [LinkController::class, 'store']);
$app->router->post('/links/edit', [LinkController::class, 'edit']);

$app->run();

function dd($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit();
}

