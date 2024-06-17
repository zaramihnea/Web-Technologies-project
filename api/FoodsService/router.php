<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "controller_Foods.php";

function handleGetRequest($segments) {
    if (count($segments) === 2 && $segments[1] === "getFoods") {
        getFoods();
    } else {
        header("Content-Type: application/json");
        header("HTTP/1.1 404 Not Found");
        die(json_encode(["error" => "Resursa nu a fost gasita"]));
    }
}

function routeRequest() {
    $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $segments = explode("/", trim($requestUri, "/"));
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            handleGetRequest($segments);
            break;
        default:
            header("Content-Type: application/json");
            header("HTTP/1.1 405 Method Not Allowed");
            die(json_encode(["error" => "Metoda nu este permisa"]));
    }
}

routeRequest();
?>
