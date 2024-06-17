<?php
header("Content-type: application/json");
include_once "model_signupService.php";

function signupService(){
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    if (!isset($data['username'], $data['password'], $data['retypepassword'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    $username = $data["username"];
    $password = $data["password"];
    $retypepassword = $data["retypepassword"];

    $control = addUser($username, $password, $retypepassword);

    if ($control === true) {
        http_response_code(200);
        echo json_encode(['message' => 'Account created']);
    } elseif ($control === -1) {
        http_response_code(400);
        echo json_encode(['error' => 'Passwords do not match']);
    } elseif ($control === -2) {
        http_response_code(400);
        echo json_encode(['error' => 'Weak password']);
    } elseif ($control === -3) {
        http_response_code(400);
        echo json_encode(['error' => 'Username is an email']);
    } elseif ($control === -4) {
        http_response_code(400);
        echo json_encode(['error' => 'Username already exists']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $control]);
    }    
}
?>