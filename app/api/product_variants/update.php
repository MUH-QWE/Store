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

$fields = [];
$params = [];

if (isset($data['name'])) { $fields[] = 'name=?'; $params[] = $data['name']; }
if (isset($data['value'])) { $fields[] = 'value=?'; $params[] = $data['value']; }
if (isset($data['price_adjustment'])) { $fields[] = 'price_adjustment=?'; $params[] = $data['price_adjustment']; }
if (isset($data['stock'])) { $fields[] = 'stock=?'; $params[] = $data['stock']; }

if (empty($fields)) Utils::jsonResponse(['message' => 'No changes']);

$sql = "UPDATE product_variants SET " . implode(',', $fields) . " WHERE id=?";
$params[] = $data['id'];

$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    Utils::jsonResponse(['message' => 'Variant updated']);
} else {
    Utils::jsonResponse(['error' => 'Failed'], 500);
}
