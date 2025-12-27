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

if (!isset($data['key'])) Utils::jsonResponse(['error' => 'Key required'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = ?");

if ($stmt->execute([$data['key']])) {
    Utils::jsonResponse(['message' => 'Setting deleted']);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
