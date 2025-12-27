<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();
AuthMiddleware::isAdmin();

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users");
Utils::jsonResponse($stmt->fetchAll());
