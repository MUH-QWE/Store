<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

if (!isset($_GET['product_id'])) Utils::jsonResponse(['error' => 'Product ID required'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ?");
$stmt->execute([$_GET['product_id']]);
Utils::jsonResponse($stmt->fetchAll());
