<?php
include_once "db.php";

function addDetails($username, $email, $firstName, $lastName, $age, $gender)
{
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $email = $mysql->real_escape_string($email);
    $firstName = $mysql->real_escape_string($firstName);
    $lastName = $mysql->real_escape_string($lastName);
    $age = (int) $age;
    $gender = (int) $gender;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        closeConnection($stmt, $mysql);
        return -1;
    }

    $query =
        "UPDATE users SET email = ?, fname = ?, lname = ?, age = ?, gender = ? WHERE username = ?;";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param(
        "sssiis",
        $email,
        $firstName,
        $lastName,
        $age,
        $gender,
        $username
    );
    $result = $stmt->execute();

    if ($result) {
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function getDetails($id)
{
    $mysql = connectToDatabase();
    $id = $mysql->real_escape_string($id);

    $query = "SELECT email, fname, lname, age, gender FROM users WHERE user_id = ?;";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }

    $stmt->bind_result($email, $fname, $lname, $age, $gender);
    $stmt->fetch();
    if($gender == 0){
        $gender = "Female";
    } elseif($gender == 1){
        $gender = "Male";
    } else {
        $gender = "Other";
    }
    $details = [
        "email" => $email,
        "fname" => $fname,
        "lname" => $lname,
        "age" => $age,
        "gender" => $gender,
    ];

    closeConnection($stmt, $mysql);
    return $details;
}

function deleteDetails($id)
{
    $mysql = connectToDatabase();
    $id = $mysql->real_escape_string($id);

    $mysql->begin_transaction();
    try {
        $stmt = $mysql->prepare(
            "DELETE FROM user_preferences WHERE user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $stmt = $mysql->prepare(
            "DELETE FROM user_shopping_list WHERE user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $stmt = $mysql->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $mysql->commit();
    } catch (mysqli_sql_exception $exception) {
        $mysql->rollback();
        throw $exception;
    }

    closeConnection($stmt, $mysql);
    return true;
}

?>