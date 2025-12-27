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

$user = AuthMiddleware::isAuthenticated();
$data = Utils::getInput();

if (!isset($data['product_id'])) {
    Utils::jsonResponse(['error' => 'Product ID required'], 400);
}

$pdo = Database::getConnection();


$stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
$stmt->execute([$user['id'], $data['product_id']]);

if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$user['id'], $data['product_id']]);
    Utils::jsonResponse(['message' => 'Added to wishlist']);
} else {
    Utils::jsonResponse(['message' => 'Already in wishlist']);
}
