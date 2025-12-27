<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Middleware\Security;

Security::headers();

if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}

Utils::jsonResponse(['message' => 'Logged out successfully']);
