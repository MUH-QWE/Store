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

if (!isset($data['id'])) Utils::jsonResponse(['error' => 'ID required'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM product_variants WHERE id = ?");
if ($stmt->execute([$data['id']])) {
    Utils::jsonResponse(['message' => 'Variant deleted']);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
