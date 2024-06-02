<?php
include_once "db.php";

function emailExists($email)
{
    $mysql = connectToDatabase();
    $stmt = $mysql->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $result = $stmt->num_rows > 0;
    closeConnection($stmt, $mysql);
    return $result;
}

?>