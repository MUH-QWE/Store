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

if (!isset($data['cart_id']) || !isset($data['quantity'])) Utils::jsonResponse(['error' => 'Missing fields'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE id = ? AND user_id = ?");
if ($stmt->execute([$data['quantity'], $data['cart_id'], $user['id']])) {
    Utils::jsonResponse(['message' => 'Cart updated']);
} else {
    Utils::jsonResponse(['error' => 'Update failed'], 500);
}
