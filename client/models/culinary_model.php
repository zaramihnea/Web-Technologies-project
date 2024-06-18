<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

//database connection
function connectToDatabase()
{
    $mysql = new mysqli(
        "localhost", // locatia serverului (aici, masina locala)
        "root", // numele de cont
        "", // parola (atentie, in clar!)
        "culinary_users" // baza de date
    );
    if (mysqli_connect_errno()) {
        die("Conexiunea a esuat...");
    }
    return $mysql;
}

//for login and signup
function getID($username)
{
    $url = "http://localhost/Proiect/api/GetId/" . $username;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return $responseData["id"];
}

function login($username, $password)
{
    $dataToBeSent = [
        "username" => $username,
        "password" => $password,
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init("http://localhost/Proiect/api/LoginService/login");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

function signup($username, $password, $retypepassword){
    $dataToBeSent = [
        "username" => $username,
        "password" => $password,
        "retypepassword" => $retypepassword,
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init("http://localhost/Proiect/api/SignupService/signup");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

function getUsernameFromEmail($email)
{
    $dataToBeSent = [
        "email" => $email,
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init("http://localhost/Proiect/api/GetUsernameService/getUsernameFromEmail");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

function checkEmail($email)
{
    $dataToBeSent = [
        "email" => $email,
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init("http://localhost/Proiect/api/EmailExistsService/emailExists");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

//for signup details and first preferences
function addDetails($username, $email, $firstName, $lastName, $age, $gender)
{
    $url = "http://localhost/Proiect/api/DetailsService/addDetails/" . $username;
    $dataToBeSent = [
        "email" => $email,
        "fname" => $firstName,
        "lname" => $lastName,
        "age" => $age,
        "gender" => $gender
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

function addPreferences($id, $preferences)
{
    foreach ($preferences as $preference) {
        $url = "http://localhost/Proiect/api/PreferenceService/addPreference/" . $id;
        $dataToBeSent = [
            "preference_id" => $preference,
        ];
        $jsonData = json_encode($dataToBeSent);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $responseData = json_decode($response, true);
    }

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

//for homepage
function addToShoppingList($user_id, $item, $quantity)
{
    $mysql = connectToDatabase();
    $item = $mysql->real_escape_string($item);

    $sql =
        "INSERT INTO user_shopping_list (user_id, item_name, quantity) VALUES (?, ?, ?)";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("isi", $user_id, $item, $quantity);
    $stmt->execute();

    if ($stmt->errno) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
}

function removeFromShoppingList($user_id, $item)
{
    $mysql = connectToDatabase();
    $item = $mysql->real_escape_string($item);

    $sql = "DELETE FROM user_shopping_list WHERE user_id = ? AND item_name = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("is", $user_id, $item);
    $stmt->execute();

    if ($stmt->errno) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
}

function getItemsFromShoppingList($user_id)
{
    $mysql = connectToDatabase();

    $sql =
        "SELECT item_name, quantity FROM user_shopping_list WHERE user_id = ?";
    $stmt = $mysql->prepare($sql);

    if (!$stmt) {
        logError("Prepare failed: " . $mysql->error);
        $mysql->close();
        return [];
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    $stmt->close();
    $mysql->close();
    return $items;
}

function checkIfItemExists($user_id, $item)
{
    $mysql = connectToDatabase();
    $item = $mysql->real_escape_string($item);

    $sql =
        "SELECT * FROM user_shopping_list WHERE user_id = ? AND item_name = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("is", $user_id, $item);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function addOneToQuantity($user_id, $item)
{
    $mysql = connectToDatabase();
    $item = $mysql->real_escape_string($item);

    $sql =
        "UPDATE user_shopping_list SET quantity = quantity + 1 WHERE user_id = ? AND item_name = ?";
    $stmt = $mysql->prepare($sql);

    $stmt->bind_param("is", $user_id, $item);
    $stmt->execute();

    if ($stmt->errno) {
        closeConnection($stmt, $mysql);
        return false;
    }

    closeConnection($stmt, $mysql);
}

function getUserPreferences($user_id)
{
    $url = "http://localhost/Proiect/api/PreferenceService/getPreferences/" . $user_id;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);

    if ($response === false) {
        $error = curl_error($curl);
        curl_close($curl);
        die(json_encode(['error' => "CURL Error: $error"]));
    }

    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($responseCode !== 200) {
        die(json_encode(['error' => "HTTP Error: $responseCode", 'response' => $response]));
    }

    $responseData = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die(json_encode(['error' => 'Invalid JSON response']));
    }

    return $responseData;
}


function addPreference($user_id, $preference)
{
    $url = "http://localhost/Proiect/api/PreferenceService/addPreference/" . $user_id;
    $dataToBeSent = [
        "preference" => $preference,
    ];
    $jsonData = json_encode($dataToBeSent);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

function getPrefID($preference, $user_id)
{
    $preference = urlencode($preference);
    $url = "http://localhost/Proiect/api/PreferenceService/getPreferenceId/" . $user_id . "/" . $preference;
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    error_log("getPrefID Response: " . print_r($response, true));

    if ($responseCode === 200 && is_numeric($response)) {
        return [
            'id' => $response,
            'responseCode' => 200
        ];
    } else {
        return [
            'responseCode' => $responseCode,
            'error' => 'Failed to get preference ID or preference ID not found',
            'responseData' => $response,
        ];
    }
}

function deletePreference($preference_id)
{
    $url = "http://localhost/Proiect/api/PreferenceService/deletePreference/" . $preference_id;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
}

//for account page
function getUserInfo($id)
{
    $url = "http://localhost/Proiect/api/DetailsService/getDetails/" . $id;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return $responseData;
}

function deleteUser($id)
{
    $url = "http://localhost/Proiect/api/DetailsService/deleteUser/" . $id;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return [
        'responseCode' => $responseCode,
        'responseData' => $responseData,
    ];
    
}

function resetPassword($oldpass, $newpass, $repeatnewpass)
{
    $mysql = connectToDatabase();
    $username = $_SESSION["username"];
    $oldpass = $mysql->real_escape_string($oldpass);
    $newpass = $mysql->real_escape_string($newpass);
    $repeatnewpass = $mysql->real_escape_string($repeatnewpass);

    $stmt = $mysql->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $password = $row["password"];

    if ($oldpass != $password) {
        closeConnection($stmt, $mysql);
        return -1;
    }

    if ($newpass != $repeatnewpass) {
        closeConnection($stmt, $mysql);
        return -2;
    }

    if (
        strlen($newpass) < 10 ||
        !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $newpass)
    ) {
        closeConnection($stmt, $mysql);
        return -3;
    }

    $stmt = $mysql->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $newpass, $username);
    $stmt->execute();

    closeConnection($stmt, $mysql);
    return true;
}

function getUserFoodsService($user_id){
    $preferences = implode(',', getUserPreferences($user_id));
    
    $url = "http://localhost/Proiect/api/FoodsService/getFoods?preferences=" . urlencode($preferences);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($curl);
    curl_close($curl);

    $responseData = json_decode($response, true);

    return $responseData;
}


//database close connection
function closeConnection($stmt, $mysql)
{
    $stmt->close();
    $mysql->close();
}
?>
