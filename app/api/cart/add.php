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
$productId = $data['product_id'];
$variantId = $data['variant_id'] ?? null;
$quantity = $data['quantity'] ?? 1;


$sql = "SELECT id, quantity FROM carts WHERE user_id = ? AND product_id = ?";
$params = [$user['id'], $productId];

if ($variantId) {
    $sql .= " AND variant_id = ?";
    $params[] = $variantId;
} else {
    $sql .= " AND variant_id IS NULL";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$existing = $stmt->fetch();

try {
    if ($existing) {
        $newQty = $existing['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQty, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id, variant_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user['id'], $productId, $variantId, $quantity]);
    }
    Utils::jsonResponse(['message' => 'Added to cart']);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Failed to add to cart'], 500);
}
