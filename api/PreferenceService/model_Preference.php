<?php
include_once "db.php";

function addPreference($id, $preference)
{
    $mysql = connectToDatabase();
    $sql = "INSERT INTO user_preferences (user_id, preference) VALUES (?, ?)";
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
    closeConnection($stmt, $mysql);
    return true;
}

function getPreferences($id){
    $mysql = connectToDatabase();
    $sql = "SELECT preference FROM user_preferences WHERE user_id = ?";
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
    $stmt->bind_result($preference);
    $preferences = [];
    while ($stmt->fetch()) {
        $preferences[] = $preference;
    }
    closeConnection($stmt, $mysql);
    return $preferences;
}

function deletePreference($id){
    $mysql = connectToDatabase();
    $sql = "DELETE FROM user_preferences WHERE preference_id = ?";
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
    closeConnection($stmt, $mysql);
    return true;
}

function getPreferenceId($preference, $user_id){
    $mysql = connectToDatabase();
    $sql = "SELECT preference_id FROM user_preferences WHERE preference = ? AND user_id = ?";
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
    $stmt->bind_result($id);
    $stmt->fetch();
    closeConnection($stmt, $mysql);
    return $id;
}

?>