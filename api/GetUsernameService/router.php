<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "controller_UsernameFromEmail.php";

function handlePostRequest($segments)
{
    if (count($segments) === 4 && $segments[3] === "getUsernameFromEmail") {
        getUsernameFromEmailService();
    } else {
        header("HTTP/1.1 404 Not Found");
        die(json_encode(["error" => "Resursa nu a fost gasita"]));
    }
}

function routeRequest()
{
    $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $segments = explode("/", trim($requestUri, "/"));
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            handlePostRequest($segments);
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
            die(json_encode(["error" => "Metoda nu este permisa"]));
    }
}
routeRequest();
?>