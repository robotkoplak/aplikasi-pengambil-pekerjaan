<?php
require_once 'config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (authenticate()) {
        $tickets = getAllTickets($pdo);
        
        http_response_code(200);
        echo json_encode($tickets);
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Unauthorized"));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (authenticate()) {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->judul) && !empty($data->deskripsi)) {
            if (createTicket($pdo, $data->judul, $data->deskripsi)) {
                http_response_code(201);
                echo json_encode(array("message" => "Ticket created successfully"));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create ticket"));
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
