<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT * FROM coupons WHERE expiry_date >= CURDATE()");
$coupons = $stmt->fetchAll();

Utils::jsonResponse($coupons);
