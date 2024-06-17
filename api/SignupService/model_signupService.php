<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "db.php";

function checkUsername($username)
{
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function addUser($username, $password, $retypepassword)
{
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);
    $retypepassword = $mysql->real_escape_string($retypepassword);

    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $mysql->close();
        return -3; // Username is an email
    }
    if(checkUsername($username)){
        $mysql->close();
        return -4; // Username already exists
    }

    if (
        strlen($password) < 10 ||
        !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)
    ) {
        $mysql->close();
        return -2; // Weak password
    }

    if ($password != $retypepassword) {
        $mysql->close();
        return -1; // Passwords do not match
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password, email, fname, lname, age, gender) VALUES (?, ?, ' ', ' ', ' ', 0, 0)";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param("ss", $username, $hashedPassword);
    $result = $stmt->execute();

    if ($result) {
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}
?>