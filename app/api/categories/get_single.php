<?php


require_once __DIR__ . '/../../bootstrap.php';
use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

if (!isset($_GET['id'])) Utils::jsonResponse(['error' => 'ID required'], 400);

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$_GET['id']]);
Utils::jsonResponse($stmt->fetch() ?: ['error' => 'Not found']);
