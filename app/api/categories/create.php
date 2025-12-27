<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();
AuthMiddleware::isAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') Utils::jsonResponse(['error' => 'Method not allowed'], 405);
$data = Utils::getInput();

if (!isset($data['name'])) Utils::jsonResponse(['error' => 'Name required'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
if ($stmt->execute([$data['name'], $data['description'] ?? ''])) {
    Utils::jsonResponse(['message' => 'Category created', 'id' => $pdo->lastInsertId()]);
} else {
    Utils::jsonResponse(['error' => 'Create failed'], 500);
}
