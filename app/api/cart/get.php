<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

$user = AuthMiddleware::isAuthenticated();
$pdo = Database::getConnection();


$sql = "SELECT c.id as cart_item_id, c.quantity, p.name, p.price, p.image, 
        v.name as variant_name, v.value as variant_value, v.price_adjustment 
        FROM carts c 
        JOIN products p ON c.product_id = p.id 
        LEFT JOIN product_variants v ON c.variant_id = v.id
        WHERE c.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user['id']]);
$cart = $stmt->fetchAll();


foreach ($cart as &$item) {
    if (isset($item['price_adjustment'])) {
        $item['price'] += $item['price_adjustment'];
    }
}

Utils::jsonResponse($cart);
