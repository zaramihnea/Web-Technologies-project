<?php
header("Content-type: application/json");
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
include_once "model_Foods.php";

function getFoods() {
    if (isset($_GET['preferences'])) {
        $preferences = explode(',', $_GET['preferences']);
        $foods = fetchFoodsByPreferences($preferences);
        echo json_encode($foods);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "No preferences provided."]);
    }
}
?>
