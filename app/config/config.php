<?php



$env = require_once __DIR__ . '/env.php';

return [
    'db' => [
        'host' => $env['DB_HOST'],
        'name' => $env['DB_NAME'],
        'user' => $env['DB_USER'],
        'pass' => $env['DB_PASS'],
    ],
    'app' => [
        'url' => $env['APP_URL'],
        'env' => $env['APP_ENV'],
        'jwt_secret' => $env['JWT_SECRET'],
    ],
    'paths' => [
        'root' => dirname(__DIR__, 2),
        'app' => dirname(__DIR__),
        'storage' => dirname(__DIR__) . '/storage',
        'uploads' => dirname(__DIR__) . '/storage/uploads',
    ]
];
