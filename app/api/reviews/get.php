<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

if (!isset($_GET['product_id'])) {
    Utils::jsonResponse(['error' => 'Product ID required'], 400);
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$stmt->execute([$_GET['product_id']]);
$reviews = $stmt->fetchAll();

Utils::jsonResponse($reviews);
