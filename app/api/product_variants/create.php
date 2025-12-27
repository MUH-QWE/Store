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

if (!isset($data['product_id']) || !isset($data['name']) || !isset($data['value'])) {
    Utils::jsonResponse(['error' => 'Missing fields'], 400);
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare("INSERT INTO product_variants (product_id, name, value, price_adjustment, stock) VALUES (?, ?, ?, ?, ?)");
if ($stmt->execute([
    $data['product_id'],
    $data['name'],
    $data['value'],
    $data['price_adjustment'] ?? 0,
    $data['stock'] ?? 0
])) {
    Utils::jsonResponse(['message' => 'Variant created', 'id' => $pdo->lastInsertId()]);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
