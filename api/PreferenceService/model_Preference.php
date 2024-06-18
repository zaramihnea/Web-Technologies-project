<?php
include_once "db.php";

function addPreference($user_id, $preference_nume)
{
    $mysql = connectToDatabase();

    $sql = "SELECT id FROM preferences WHERE nume = ?";
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
        $sql = "INSERT INTO preferences (nume) VALUES (?)";
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


function getPreferences($user_id)
{
    $mysql = connectToDatabase();
    $sql = "SELECT p.name 
            FROM user_preferences up 
            JOIN preferences p ON up.preference_id = p.id 
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


function deletePreference($preference_id, $user_id)
{
    $mysql = connectToDatabase();
    $sql = "DELETE FROM user_preferences WHERE preference_id = ? AND user_id = ?";
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $stmt->bind_param("ii", $preference_id, $user_id);
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    closeConnection($stmt, $mysql);
    return true;
}


function getPreferenceId($preference_nume, $user_id)
{
    $mysql = connectToDatabase();
    $sql = "SELECT id FROM preferences WHERE nume = ?";
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $stmt->bind_param("s", $preference_nume);
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_result($preference_id);
    $stmt->fetch();
    closeConnection($stmt, $mysql);
    return $preference_id;
}
?>