<?php


namespace App\Middleware;

use App\Helpers\Auth as AuthHelper;
use App\Helpers\Utils;

class AuthMiddleware {
    public static function isAuthenticated() {
        $user = AuthHelper::user();
        if (!$user) {
            Utils::jsonResponse(['error' => 'Unauthorized'], 401);
        }
        return $user;
    }

    public static function isAdmin() {
        $user = self::isAuthenticated();
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            Utils::jsonResponse(['error' => 'Forbidden: Admins only'], 403);
        }
        return $user;
    }
}
