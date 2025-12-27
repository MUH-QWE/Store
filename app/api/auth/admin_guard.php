<?php



require_once __DIR__ . '/../../bootstrap.php';
use App\Middleware\AuthMiddleware;

AuthMiddleware::isAdmin();
