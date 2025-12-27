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

if (!isset($data['product_id']) || !isset($data['rating']) || !isset($data['comment'])) {
    Utils::jsonResponse(['error' => 'Missing fields'], 400);
}

$pdo = Database::getConnection();

try {
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user['id'], $data['product_id'], $data['rating'], $data['comment']]);
    Utils::jsonResponse(['message' => 'Review added']);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Failed to add review'], 500);
}
