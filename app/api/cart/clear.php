<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') Utils::jsonResponse(['error' => 'Method not allowed'], 405);
$user = AuthMiddleware::isAuthenticated();

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
if ($stmt->execute([$user['id']])) {
    Utils::jsonResponse(['message' => 'Cart cleared']);
} else {
    Utils::jsonResponse(['error' => 'Clear failed'], 500);
}
