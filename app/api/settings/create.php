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

if (!isset($data['key']) || !isset($data['value'])) Utils::jsonResponse(['error' => 'Missing fields'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");

try {
    $stmt->execute([$data['key'], $data['value']]);
    Utils::jsonResponse(['message' => 'Setting created']);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Failed (maybe key exists)'], 500);
}
