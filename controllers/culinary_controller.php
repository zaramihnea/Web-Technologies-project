<?php
require_once '../models/culinary_model.php';

session_start();

//preferences page
if(isset($_GET['action']) && $_GET['action'] === 'modifyPref') {
    $preferences = $_POST['preference'];
    $oldPreferences = getUserPreferences($_SESSION['id']);
    $preferencesToDelete = array_diff($oldPreferences, $preferences);
    $preferencesToAdd = array_diff($preferences, $oldPreferences);

    if(empty($preferencesToDelete) && empty($preferencesToAdd)) {
        $msg = "No changes made!";
    } elseif(empty($preferencesToDelete)) {
        foreach ($preferencesToAdd as $preference) {
            $prefid = getPrefID($preference);
            addPreference($_SESSION['id'], $prefid);
        }
        $preferences = getUserPreferences($_SESSION['id']);
        $msg = "Preferences saved!";
    } elseif(empty($preferencesToAdd)) {
        foreach ($preferencesToDelete as $preference) {
            deletePreference($_SESSION['id'], $preference);
        }
        $preferences = getUserPreferences($_SESSION['id']);
        $msg = "Preferences saved!";
    }
    
    include '../views/preferinte.php';
    exit();
}


if(isset($_GET['action']) && $_GET['action'] === 'redirectPrefs') {
    $preferences = getUserPreferences($_SESSION['id']);
    include '../views/preferinte.php';
    exit();
}


//add first preferences
if(isset($_GET['action']) && $_GET['action'] === 'pref1') {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $username = $_SESSION['username'];

    $existEmail = checkEmail($email);
    if($existEmail) {
        $errorMessage3 = "Email already exists!";
        include '../views/personalization1.php';
        exit();
    }

    $isValid = addPref1($username, $email, $firstName, $lastName, $age, $gender);
    if($isValid === -1) {
        $errorMessage3 = "Invalid email format!";
        include '../views/personalization1.php';
        exit();
    }

    if($isValid) {
        include '../views/personalization2.html';
        exit();
    } else {
        $errorMessage3 = "Invalid input!";
        include '../views/personalization1.html';
        exit();
    }
}


//signup
if(isset($_GET['action']) && $_GET['action'] === 'addCred') {
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    $retypepassword = $_POST['password2'];
    
    $usernameExists = checkUsername($username);
    if($usernameExists) {
        $errorMessage2 = "Username already exists!";
        include '../views/signup.php';
        exit();
    }
    $isValid = addUser($username, $password, $retypepassword);

    if($isValid === -3) {
        $errorMessage2 = "Username cannot be an email!";
        include '../views/signup.php';
        exit();
    }

    if($isValid === -2) {
        $errorMessage2 = "Weak password! (must be at least 10 characters long and contain at least one special character)";
        include '../views/signup.php';
        exit();
    }
    
    if($isValid === -1) {
        $errorMessage2 = "Passwords do not match!";
        include '../views/signup.php';
        exit();
    }
    
    if($isValid) {
        include '../views/personalization1.php';
        exit();
    } else {
        $errorMessage2 = "Username already exists!";
        include '../views/signup.php';
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'signup') {
    include '../views/signup.php';
    exit();
}


//login
if(isset($_GET['action']) && $_GET['action'] === 'checkCred') {
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    
    $isValid = checkUserCredentials($username, $password);
    
    if($isValid) {
        if(isset($_POST["rememberme"])) {
            setcookie("rememberme", $username, time()+(60*60*24*1));
        }
        include '../views/homepage.php';
        exit();
    } else {
        $errorMessage = "Invalid username or password!";
        include '../views/login.php';
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'login') {
    if(isset($_COOKIE["rememberme"])){
        include '../views/homepage.php';
        exit();
    }
    else{
        include '../views/login.php';
        exit();
    }
}

include '../views/index.html';

?>
