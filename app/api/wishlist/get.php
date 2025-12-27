<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

$user = AuthMiddleware::isAuthenticated();
$pdo = Database::getConnection();

$sql = "SELECT w.id as wishlist_id, p.* 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user['id']]);
$items = $stmt->fetchAll();

Utils::jsonResponse($items);
