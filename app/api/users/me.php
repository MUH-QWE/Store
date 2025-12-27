<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

$user = AuthMiddleware::isAuthenticated();


unset($user['password']);
Utils::jsonResponse($user);
