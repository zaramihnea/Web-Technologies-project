<?php
header("Content-type: application/json");
include_once "model_getID.php";

function getIdService($username){
    $id = getID($username);
    if($id){
        echo json_encode(["id" => $id]);
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["error" => "Resursa nu a fost gasita"]);
    }
}

?>