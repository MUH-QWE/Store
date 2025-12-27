<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;
use App\Middleware\AuthMiddleware;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Utils::jsonResponse(['error' => 'Method not allowed'], 405);
}

$user = AuthMiddleware::isAuthenticated();
$pdo = Database::getConnection();


$stmt = $pdo->prepare("SELECT c.quantity, c.variant_id, p.id as product_id, p.price, v.price_adjustment 
                       FROM carts c 
                       JOIN products p ON c.product_id = p.id 
                       LEFT JOIN product_variants v ON c.variant_id = v.id 
                       WHERE c.user_id = ?");
$stmt->execute([$user['id']]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    Utils::jsonResponse(['error' => 'Cart is empty'], 400);
}

try {
    $pdo->beginTransaction();

    
    $total = 0;
    foreach ($cartItems as &$item) {
        $price = $item['price'];
        if (isset($item['price_adjustment'])) {
            $price += $item['price_adjustment'];
        }
        $item['final_price'] = $price;
        $total += $price * $item['quantity'];
    }

    
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'paid')");
    $stmt->execute([$user['id'], $total]);
    $orderId = $pdo->lastInsertId();

    
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, variant_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmtItem->execute([$orderId, $item['product_id'], $item['variant_id'], $item['quantity'], $item['final_price']]);
    }

    
    $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
    $stmt->execute([$user['id']]);

    $pdo->commit();
    Utils::jsonResponse(['message' => 'Order placed successfully', 'order_id' => $orderId]);

} catch (Exception $e) {
    $pdo->rollBack();
    Utils::jsonResponse(['error' => 'Failed to place order: ' . $e->getMessage()], 500);
}
