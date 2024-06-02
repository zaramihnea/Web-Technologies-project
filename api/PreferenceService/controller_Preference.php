<?php
header("Content-Type: application/json");
include_once "model_Preference.php";

function addPreferenceService($id){
    $data = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        return;
    }

    if (!isset($data['preference'])|| empty($data['preference'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $preference = $data['preference'];

    $result = addPreference($id, $preference);

    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => 'Preference added']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add preference']);
    }

}

function getPreferencesService($id){
    $preferences = getPreferences($id);

    if ($preferences) {
        http_response_code(200);
        echo json_encode($preferences);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get preferences']);
    }
}

function deletePreferenceService($id){
    $result = deletePreference($id);

    if ($result) {
        http_response_code(200);
        echo json_encode(['success' => 'Preference deleted']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete preference']);
    }
}

function getPreferenceIdService($preference, $id){
    $id = getPreferenceId($preference, $id);

    if ($id) {
        http_response_code(200);
        echo json_encode($id);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to get preference id']);
    }
}

?>