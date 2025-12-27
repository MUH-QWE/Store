<?php




$config = require_once __DIR__ . '/config/config.php';


require_once __DIR__ . '/config/autoload.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



global $pdo;
$pdo = App\Helpers\Database::connect($config['db']);
