<?php
require_once '../models/culinary_model.php';



if(isset($_GET['action']) && $_GET['action'] === 'checkCred') {
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    
    $isValid = checkUserCredentials($username, $password);
    
    if($isValid) {
        if(isset($_POST["rememberme"])) {
            setcookie("rememberme", $username, time()+(60*60*24*1));
        }
        include '../views/homepage.html';
        exit();
    } else {
        $errorMessage = "Invalid username or password!";
        include '../views/login.php';
        exit();
    }
}

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
    
    if($isValid === -1) {
        $errorMessage2 = "Passwords do not match!";
        include '../views/signup.php';
        exit();
    }
    
    if($isValid) {
        include '../views/personalization1.html';
        exit();
    } else {
        $errorMessage2 = "Username already exists!";
        include '../views/signup.php';
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'login') {
    if(isset($_COOKIE["rememberme"])){
        include '../views/homepage.html';
        exit();
    }
    else{
        include '../views/login.php';
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'signup') {
    include '../views/signup.php';
    exit();
}
include '../views/index.html';

?>
