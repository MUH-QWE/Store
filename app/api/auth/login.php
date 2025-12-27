<?php


require_once __DIR__ . '/../../bootstrap.php';

use App\Helpers\Utils;
use App\Helpers\Database;
use App\Helpers\Auth;
use App\Middleware\Security;

Security::headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Utils::jsonResponse(['error' => 'Method not allowed'], 405);
}

$data = Utils::getInput();

if (!isset($data['email']) || !isset($data['password'])) {
    Utils::jsonResponse(['error' => 'Missing credentials'], 400);
}

$pdo = Database::getConnection();
$stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
$user = $stmt->fetch();

if ($user && password_verify($data['password'], $user['password'])) {
    
    $token = Auth::generateToken([
        'id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + (60 * 60 * 24) 
    ]);
    
    
    $_SESSION['user'] = [
        'id' => $user['id'],
        'role' => $user['role']
    ];

    Utils::jsonResponse([
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ]
    ]);
} else {
    Utils::jsonResponse(['error' => 'Invalid credentials'], 401);
}
