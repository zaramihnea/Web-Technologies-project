<?php
include_once "db.php";

function addPreference($id, $preference)
{
    $mysql = connectToDatabase();
    
    $sql = "
        INSERT INTO user_preferences (user_id, preference_id)
        SELECT ?, p.id
        FROM preferences p
        WHERE p.name = ?";
        
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    $stmt->bind_param("is", $id, $preference);
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

function getPreferences($id){
    $mysql = connectToDatabase();
    
    $sql = "
        SELECT p.name 
        FROM user_preferences up 
        JOIN preferences p ON up.preference_id = p.id 
        WHERE up.user_id = ?";
        
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    
    $stmt->bind_param("i", $id);
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

function deletePreference($preference, $user_id){
    $mysql = connectToDatabase();
    
    $sql = "
        DELETE up
        FROM user_preferences up
        JOIN preferences p ON up.preference_id = p.id
        WHERE p.name = ? AND up.user_id = ?";
        
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    
    $stmt->bind_param("si", $preference, $user_id);
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    closeConnection($stmt, $mysql);
    return true;
}

function getPreferenceId($preference, $user_id) {
    $mysql = connectToDatabase();
    
    $sql = "
        SELECT up.preference_id
        FROM user_preferences up
        JOIN preferences p ON up.preference_id = p.id
        WHERE p.name = ? AND up.user_id = ?";
    
    $stmt = $mysql->prepare($sql);
    if (!$stmt) {
        $mysql->close();
        return false;
    }
    
    $stmt->bind_param("si", $preference, $user_id);
    $result = $stmt->execute();
    if (!$result) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    $stmt->bind_result($preference_id);
    if (!$stmt->fetch()) {
        closeConnection($stmt, $mysql);
        return false;
    }
    
    closeConnection($stmt, $mysql);
    return $preference_id;
}

?>