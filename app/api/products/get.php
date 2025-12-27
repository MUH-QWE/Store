<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

$pdo = Database::getConnection();

$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
$params = [];

if (isset($_GET['category_id'])) {
    $sql .= " WHERE p.category_id = ?";
    $params[] = $_GET['category_id'];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

Utils::jsonResponse($products);
