<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "db.php";

function authenticate($username, $password)
{
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $query = "SELECT password FROM users WHERE username = ? OR email = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    

    if ($user && isset($user['password'])) {
        if (password_verify($password, $user['password'])) {
            return true;
        }
    }
    return false;
}


?>
