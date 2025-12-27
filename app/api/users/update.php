<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') Utils::jsonResponse(['error' => 'Method not allowed'], 405);
$user = AuthMiddleware::isAuthenticated();
$data = Utils::getInput();


$pdo = Database::getConnection();


if (isset($data['name'])) {
    $stmt = $pdo->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->execute([$data['name'], $user['id']]);
}
Utils::jsonResponse(['message' => 'User updated']);
