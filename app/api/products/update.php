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

$fields = [];
$params = [];

if (isset($data['name'])) { $fields[] = 'name = ?'; $params[] = $data['name']; }
if (isset($data['price'])) { $fields[] = 'price = ?'; $params[] = $data['price']; }
if (isset($data['description'])) { $fields[] = 'description = ?'; $params[] = $data['description']; }
if (isset($data['stock'])) { $fields[] = 'stock = ?'; $params[] = $data['stock']; }
if (isset($data['image'])) { $fields[] = 'image = ?'; $params[] = $data['image']; }

if (empty($fields)) {
    Utils::jsonResponse(['message' => 'No changes provided']);
}

$sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?";
$params[] = $data['id'];

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    Utils::jsonResponse(['message' => 'Product updated']);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Update failed'], 500);
}
