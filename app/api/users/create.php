<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Middleware\Security;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Utils::jsonResponse(['error' => 'Method not allowed'], 405);
}

$data = Utils::getInput();

if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    Utils::jsonResponse(['error' => 'Missing fields'], 400);
}

$pdo = Database::getConnection();


$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
if ($stmt->fetch()) {
    Utils::jsonResponse(['error' => 'User already exists'], 409);
}


$password = password_hash($data['password'], PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

try {
    $stmt->execute([$data['name'], $data['email'], $password]);
    Utils::jsonResponse(['message' => 'User registered successfully'], 201);
} catch (Exception $e) {
    Utils::jsonResponse(['error' => 'Registration failed'], 500);
}
