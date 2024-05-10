<?php

//database connection
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


//for login and signup
function getID($username) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);

    $query = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        closeConnection($stmt, $mysql);
        return $row['user_id'];
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function checkUserCredentials($username, $password) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);

    $query = "SELECT * FROM users WHERE (username = ? AND password = ?) OR (email = ? AND password = ?);";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('ssss', $username, $password, $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        if(filter_var($username, FILTER_VALIDATE_EMAIL)){
            $username = getUsernameFromEmail($username);
            if (!$username) {
                closeConnection($stmt, $mysql);
                return false;
            }
        }
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['id'] = getID($username);
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function getUsernameFromEmail($email) {
    $mysql = connectToDatabase();
    $email = $mysql->real_escape_string($email);

    $query = "SELECT username FROM users WHERE email = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        closeConnection($stmt, $mysql);
        return $row['username'];
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function checkEmail($email) {
    $mysql = connectToDatabase();
    $email = $mysql->real_escape_string($email);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('s', $email);
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

function checkUsername($username) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);


    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false; // Prepare failed
    }
    $stmt->bind_param('s', $username);
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

function addUser($username, $password, $retypepassword) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);
    $retypepassword = $mysql->real_escape_string($retypepassword);

    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        closeConnection($stmt, $mysql);
        return -3; // Username is an email
    }

    if (strlen($password) < 10 || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
        closeConnection($stmt, $mysql);
        return -2; // Weak password
    }

    if ($password != $retypepassword) {
        closeConnection($stmt, $mysql);
        return -1; // Passwords do not match
    }

    $query = "INSERT INTO users (username, password, email, fname, lname, age, gender) VALUES (?, ?, ' ', ' ', ' ', 0, 0)";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false; // Prepare failed
    }
    $stmt->bind_param('ss', $username, $password);
    $result = $stmt->execute();

    if ($result) {
        session_start();
        $_SESSION['username'] = $username;
        if (!isset($_SESSION['username'])) {
            closeConnection($stmt, $mysql);
            return false;
        }
        $_SESSION['id'] = getID($username);
        if (!isset($_SESSION['id'])) {
            closeConnection($stmt, $mysql);
            return false;
        }
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}


//for first preferences
function addPref1($username, $email, $firstName, $lastName, $age, $gender){
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $email = $mysql->real_escape_string($email);
    $firstName = $mysql->real_escape_string($firstName);
    $lastName = $mysql->real_escape_string($lastName);
    $age = (int)$age;
    $gender = (int)$gender;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        closeConnection($stmt, $mysql);
        return -1;
    }

    $query = "UPDATE users SET email = ?, fname = ?, lname = ?, age = ?, gender = ? WHERE username = ?;";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('sssiis', $email, $firstName, $lastName, $age, $gender, $username);
    $result = $stmt->execute();
    
    if ($result) {
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function addPref2(){

}

//for preferences page
function getUserPreferences($user_id) {
    $mysql = connectToDatabase();

    $sql = "SELECT preference FROM user_preferences WHERE user_id = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $preferences = array();
    while ($row = $result->fetch_assoc()) {
        $preferences[] = $row['preference'];
    }

    closeConnection($stmt, $mysql);
    return $preferences;
}


function addPreference($user_id, $preference) {
    $mysql = connectToDatabase();

    $sql = "INSERT INTO user_preferences (user_id, preference) VALUES (?, ?)";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("is", $user_id, $preference);
    $stmt->execute();

    if ($stmt->errno) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
}

function getPrefID($preference) {
    $mysql = connectToDatabase();

    $sql = "SELECT preference_id FROM user_preferences WHERE preference = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("s", $preference);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    closeConnection($stmt, $mysql);
    return $row['preference_id'];
}

function deletePreference($user_id, $preference) {
    $mysql = connectToDatabase();

    $sql = "DELETE FROM user_preferences WHERE user_id = ? AND preference = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("ii", $user_id, $preference);
    $stmt->execute();
    if ($stmt->errno) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
}

//database close connection
function closeConnection($stmt, $mysql) {
    $stmt->close();
    $mysql->close();
}
?>
