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

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM coupons WHERE id=?");
if ($stmt->execute([$data['id']])) {
    Utils::jsonResponse(['message' => 'Coupon deleted']);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
