<?php
header("Content-type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "model_UsernameFromEmail.php";

function getUsernameFromEmailService()
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    if (!isset($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    $email = $data["email"];
               
    $username = getUsernameFromEmail($email);
    if($username){
        http_response_code(200);
        echo json_encode(['username' => $username]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Username not found']);
    }
    
}

?>