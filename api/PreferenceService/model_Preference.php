<?php
include_once "db.php";

function addPreference($user_id, $preference_nume)
{
    $mysql = connectToDatabase();

    $sql = "SELECT id FROM Preferences WHERE name = ?";
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $stmt->bind_param("s", $preference_nume);
    $stmt->execute();
    $stmt->bind_result($preference_id);
    $stmt->fetch();
    $stmt->close();

    if (!$preference_id) {
        $sql = "INSERT INTO Preferences (name) VALUES (?)";
        $stmt = $mysql->prepare($sql);
        if (!$stmt) {
            $mysql->close();
            return false;
        }
        $stmt->bind_param("s", $preference_nume);
        $stmt->execute();
        $preference_id = $stmt->insert_id;
        $stmt->close();
    }

    $sql = "INSERT INTO user_preferences (user_id, preference_id) VALUES (?, ?)";
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $stmt->bind_param("ii", $user_id, $preference_id);
    $result = $stmt->execute();
    
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }

    if ($stmt->affected_rows === 0) {
        closeConnection($stmt, $mysql);
        return false; // Preference not found
    }
    
    closeConnection($stmt, $mysql);
    return true;
}

function getAllPreferences()
{
    $mysql = connectToDatabase();
    $sql = "SELECT name FROM Preferences";
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    $stmt->bind_result($preference_name);
    $preferences = [];
    while ($stmt->fetch()) {
        $preferences[] = $preference_name;
    }
    
    closeConnection($stmt, $mysql);
    return $preferences;
}


function getPreferences($user_id)
{
    $mysql = connectToDatabase();
    $sql = "SELECT p.name 
            FROM user_preferences up 
            JOIN Preferences p ON up.preference_id = p.id 
            WHERE up.user_id = ?";
    $stmt = $mysql->prepare($sql);

    if (!$stmt) {
        $mysql->close();
        return "1";
    }
    $stmt->bind_param("i", $user_id);
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    $stmt->bind_result($preference_name);
    $preferences = [];
    while ($stmt->fetch()) {
        $preferences[] = $preference_name;
    }
    
    closeConnection($stmt, $mysql);
    return $preferences;
}


function deletePreference($preference_id) {
    $mysql = connectToDatabase();
    $sql = "DELETE FROM user_preferences WHERE id = ?";
    $stmt = $mysql->prepare($sql);

    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }

    $stmt->bind_param("i", $preference_id);
    $result = $stmt->execute();

    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
    return true;
}



function getPreferenceId($preferenceName, $userId) {
    $mysql = connectToDatabase();
    
    $query = "
        SELECT up.id AS user_preference_id
        FROM user_preferences up
        JOIN preferences p ON up.preference_id = p.id
        WHERE p.name = ? AND up.user_id = ?";
    
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $mysql->error;
        closeConnection(null, $mysql);
        return false;
    }
    
    $stmt->bind_param("si", $preferenceName, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo "No user preference found with user ID: $userId and preference name: $preferenceName";
        closeConnection($stmt, $mysql);
        return false;
    }

    $row = $result->fetch_assoc();
    $userPreferenceId = $row['user_preference_id'];
    
    closeConnection($stmt, $mysql);
    return $userPreferenceId;
}
?>