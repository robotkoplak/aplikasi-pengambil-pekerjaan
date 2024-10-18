<?php
require_once 'config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (authenticate()) {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->ticket_id) && !empty($data->status)) {
            if (updateTicketStatus($pdo, $data->ticket_id, $data->status)) {
                http_response_code(200);
                echo json_encode(array("message" => "Ticket updated successfully"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update ticket"));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data"));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Unauthorized"));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed"));
}
