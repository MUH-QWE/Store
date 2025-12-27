<?php


namespace App\Helpers;

class Utils {
    public static function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    public static function sanitize($input) {
        if (is_array($input)) {
            foreach($input as $k => $v) {
                $input[$k] = self::sanitize($v);
            }
            return $input;
        }
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function getInput() {
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return self::sanitize($data);
        }
        
        
        return self::sanitize($_REQUEST);
    }
}
