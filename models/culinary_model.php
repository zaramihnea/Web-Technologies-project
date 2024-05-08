<?php
function connectToDatabase() {
    $mysql = new mysqli(
        'localhost', // locatia serverului (aici, masina locala)
        'root',      // numele de cont
        '',          // parola (atentie, in clar!)
        'culinary_users'   // baza de date
    );
    if (mysqli_connect_errno()) {
        die ('Conexiunea a esuat...');
    }
    return $mysql;
}

function checkUserCredentials($username, $password) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $mysql->query($query);

    if ($result && $result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function checkUsername($username) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $mysql->query($query);

    if ($result && $result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function addUser($username, $password, $retypepassword) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);
    $retypepassword = $mysql->real_escape_string($retypepassword);

    if ($password != $retypepassword) {
        return -1;
    }
    $query = "INSERT INTO users (username, password, email, fname, lname, age, gender) VALUES ('$username', '$password', ' ', ' ', ' ', 0, 0)";
    $result = $mysql->query($query);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function addPref1(){

}
function closeConnection($mysql) {
    $mysql->close();
}
?>
