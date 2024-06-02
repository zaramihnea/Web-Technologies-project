<?php
include_once "db.php";

function getID($username)
{
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);

    $query = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        closeConnection($stmt, $mysql);
        return $row["user_id"];
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

?>