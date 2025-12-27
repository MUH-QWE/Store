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

$pdo = Database::getConnection();

$stmt = $pdo->prepare("SELECT user_id FROM reviews WHERE id = ?");
$stmt->execute([$data['id']]);
$review = $stmt->fetch();

if (!$review) Utils::jsonResponse(['error' => 'Review not found'], 404);
if ($review['user_id'] != $user['id'] && $user['role'] !== 'admin') {
    Utils::jsonResponse(['error' => 'Unauthorized'], 403);
}

$stmt = $pdo->prepare("UPDATE reviews SET rating=?, comment=? WHERE id=?");
if ($stmt->execute([$data['rating'], $data['comment'], $data['id']])) {
    Utils::jsonResponse(['message' => 'Review updated']);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
