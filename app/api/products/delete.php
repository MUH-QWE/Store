<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Utils::jsonResponse(['error' => 'Method not allowed'], 405);
}

AuthMiddleware::isAdmin();

$data = Utils::getInput();

if (!isset($data['id'])) {
    Utils::jsonResponse(['error' => 'Product ID required'], 400);
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");

if ($stmt->execute([$data['id']])) {
    Utils::jsonResponse(['message' => 'Product deleted']);
} else {
    Utils::jsonResponse(['error' => 'Delete failed'], 500);
}
