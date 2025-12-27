<?php


namespace App\Helpers;

use Exception; 

class Auth {
    
    

    public static function generateToken($payload) {
        
        
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $env = require __DIR__ . '/../config/env.php';
        $secret = $env['JWT_SECRET'];
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function verifyToken($token) {
        $env = require __DIR__ . '/../config/env.php';
        $secret = $env['JWT_SECRET'];
        
        $parts = explode('.', $token);
        if(count($parts) !== 3) return false;
        
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if ($base64UrlSignature === $base64UrlSignatureCheck) {
            return json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $base64UrlPayload)), true);
        }
        return false;
    }

    public static function user() {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return self::verifyToken($matches[1]);
        }
        return null;
    }
}
