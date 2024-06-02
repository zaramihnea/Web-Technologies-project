<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "db.php";

function getUsernameFromEmail($email)
{
    $mysql = connectToDatabase();
    $stmt = $mysql->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    closeConnection($stmt, $mysql);

    return $username;
}

?>