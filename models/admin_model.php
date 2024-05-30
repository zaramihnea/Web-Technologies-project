<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    $query = "SELECT user_id FROM admins WHERE username = ?";
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
        return $row['admin_id'];
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function checkAdminCredentials($username, $password) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);

    $query = "SELECT * FROM admins WHERE (username = ? AND password = ?)";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('ss', $username, $password);
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
        $_SESSION['username'] = $username;
        $_SESSION['id'] = getID($username);
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function getPreferences() {
    $mysql = connectToDatabase();
    $query = "SELECT name FROM Preferences";
    
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $names = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $names[] = $row['name'];
        }
    }
    
    closeConnection($stmt, $mysql);
    return $names;
}

function getPreferenceUsage() {
    $mysql = connectToDatabase();
    $query = "SELECT p.name, COUNT(up.preference_id) AS count
              FROM preferences p
              LEFT JOIN user_preferences up ON p.id = up.preference_id
              GROUP BY p.name
              ORDER BY p.name";

    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $preferencesUsage = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $preferencesUsage[$row['name']] = $row['count'];
        }
    }

    closeConnection($stmt, $mysql);
    return $preferencesUsage;
}

function getUsers() {
    $mysql = connectToDatabase();
    $query = "SELECT user_id, username, email, fname, lname, age, gender FROM users";

    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'email' => $row['email'],
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'age' => $row['age'],
                'gender' => $row['gender']
            ];
        }
    }

    closeConnection($stmt, $mysql);
    return $users;
}

function deleteUser($userId) {
    $mysql = connectToDatabase();
    $userId = $mysql->real_escape_string($userId);

    $mysql->begin_transaction();
    try {
        // Delete from user_preferences
        $stmt = $mysql->prepare("DELETE FROM user_preferences WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception($mysql->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete from Users
        $stmt = $mysql->prepare("DELETE FROM users WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception($mysql->error);
        }
        $stmt->bind_param("i", $userId);  
        $stmt->execute();
        $stmt->close();

        $mysql->commit();
    } catch (mysqli_sql_exception $exception) {
        $mysql->rollback();
        throw $exception;
    }

    closeConnection(null, $mysql);
}



function closeConnection($stmt, $mysql) {
    if ($stmt && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if ($mysql && $mysql instanceof mysqli) {
        $mysql->close();
    }
}
?>
