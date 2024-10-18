<?php
require_once 'config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->username) && !empty($data->password)) {
        $user = getUserByUsername($pdo, $data->username);
        
        if ($user && password_verify($data->password, $user['password'])) {
            // Generate token (implementasi sesuai kebutuhan)
            $token = bin2hex(random_bytes(16));
            
            http_response_code(200);
            echo json_encode(array(
                "message" => "Login successful",
                "token" => $token,
                "user_id" => $user['id'],
                "username" => $user['username'],
                "role" => $user['role']
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Login failed"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed"));
}
