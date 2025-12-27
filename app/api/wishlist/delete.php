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

if (!isset($data['wishlist_id'])) Utils::jsonResponse(['error' => 'Missing fields'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM wishlist WHERE id = ? AND user_id = ?");
if ($stmt->execute([$data['wishlist_id'], $user['id']])) {
    Utils::jsonResponse(['message' => 'Removed from wishlist']);
} else {
    Utils::jsonResponse(['error' => 'Delete failed'], 500);
}
