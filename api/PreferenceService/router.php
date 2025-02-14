<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "controller_Preference.php";

function handlePostRequest($segments)
{
    if (count($segments) === 5 && $segments[3] === "addPreference") {
        $id = $segments[4];
        addPreferenceService($id);
    } else {
        header("HTTP/1.1 404 Not Found");
        die(json_encode(["error" => "Resursa nu a fost gasita"]));
    }
}

function handleGetRequest($segments)
{
    if (count($segments) === 5 && $segments[3] === "getPreferences") {
        $id = $segments[4];
        getPreferencesService($id);
    } else 
    if (count($segments) === 6 && $segments[3] === "getPreferenceId") {
        $id = $segments[4];
        $preference = $segments[5];
        getPreferenceIdService($preference, $id);
    } else 
    if (count($segments) === 4 && $segments[3] === "getAllPreferences") {
        getAllPreferencesService();
    } else {
        header("HTTP/1.1 404 Not Found");
        die(json_encode(["error" => "Resursa nu a fost gasita"]));
    }
}

function handleDeleteRequest($segments)
{
    if (count($segments) === 5 && $segments[3] === "deletePreference") {
        $id = $segments[4];
        deletePreferenceService($id);
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
        case "GET":
            handleGetRequest($segments);
            break;
        case "DELETE":
            handleDeleteRequest($segments);
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
            die(json_encode(["error" => "Metoda nu este permisa"]));
    }
}
routeRequest();
?>