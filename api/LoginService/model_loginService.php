<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require_once "db.php";

function authenticate($username_or_email, $password)
{
    $mysql = connectToDatabase();
    if (!$mysql) {
        echo "Failed to connect to the database.";
        return false;
    }

    $query = "SELECT password FROM users WHERE username = ? OR email = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $mysql->errno . ") " . $mysql->error;
        $mysql->close();
        return false;
    }

    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        closeConnection($stmt, $mysql);
        return false;
    }

    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    $is_authenticated = false;
    if (password_verify($password, $hashed_password)) {
        $is_authenticated = true;
    }

    closeConnection($stmt, $mysql);

    return $is_authenticated;
}


?>
