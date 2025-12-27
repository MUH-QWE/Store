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

if (!isset($data['name']) || !isset($data['price'])) {
    Utils::jsonResponse(['error' => 'Missing required fields'], 400);
}

$pdo = Database::getConnection();

$stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category_id, image) VALUES (?, ?, ?, ?, ?, ?)");

$image = $data['image'] ?? null; 

try {
    $stmt->execute([
        $data['name'],
        $data['description'] ?? '',
        $data['price'],
        $data['stock'] ?? 0,
        $data['category_id'] ?? null,
        $image
    ]);
    Utils::jsonResponse(['message' => 'Product created', 'id' => $pdo->lastInsertId()], 201);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Failed to create product'], 500);
}
