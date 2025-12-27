<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();
$user = AuthMiddleware::isAuthenticated();
$data = $_GET;

if (!isset($data['id'])) Utils::jsonResponse(['error' => 'ID required'], 400);


if ($user['id'] != $data['id'] && $user['role'] !== 'admin') {
    Utils::jsonResponse(['error' => 'Unauthorized'], 403);
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
$stmt->execute([$data['id']]);
Utils::jsonResponse($stmt->fetch() ?: ['error'=>'Not found']);
