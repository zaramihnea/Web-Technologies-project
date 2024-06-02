<?php
header("Content-Type: application/json");
include_once "model_Details.php";

function addDetailsService($username)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    if (!isset($data['email'], $data['fname'], $data['lname'], $data['age'], $data['gender'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $email = $data['email'];
    $fname = $data['fname'];
    $lname = $data['lname'];
    $age = $data['age'];
    $gender = $data['gender'];

    $control = addDetails($username, $email, $fname, $lname, $age, $gender);
    if ($control === -1) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email']);
        return;
    }

    if ($control) {
        http_response_code(200);
        echo json_encode(['success' => 'Details updated']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Details not updated']);
    }
}

function getDetailsService($id)
{
    $details = getDetails($id);

    if ($details) {
        http_response_code(200);
        echo json_encode($details);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get details']);
    }
}

function updateDetailsService($id)
{
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    if (!isset($data['email'], $data['fname'], $data['lname'], $data['age'], $data['gender'])){
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $email = $data['email'];
    $fname = $data['fname'];
    $lname = $data['lname'];
    $age = $data['age'];
    $gender = $data['gender'];

    $control = addDetails($id, $email, $fname, $lname, $age, $gender);
    if ($control === -1) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email']);
        return;
    }

    if ($control) {
        http_response_code(200);
        echo json_encode(['success' => 'Details updated']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Details not updated']);
    }
}

function deleteDetailsService($id)
{
    $control = deleteDetails($id);
    if ($control) {
        http_response_code(200);
        echo json_encode(['success' => 'Details deleted']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Details not deleted']);
    }
}

?>