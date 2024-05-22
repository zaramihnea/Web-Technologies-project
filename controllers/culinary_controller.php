<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../models/culinary_model.php';

session_start();

//POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            //login page
            case 'checkCred':
                $username = $_POST['user_name'];
                $password = $_POST['password'];
    
                $isValid = checkUserCredentials($username, $password);
    
                if($isValid) {
                    if(isset($_POST["rememberme"])) {
                        setcookie("rememberme", $username, time()+(60*60*24*1));
                    }
                    include '../views/user/homepage.php';
                    exit();
                } else {
                    $errorMessage = "Invalid username or password!";
                    include '../views/user/login.php';
                    exit();
                }

            //signup page
            case 'addCred':
                $username = $_POST['user_name'];
                $password = $_POST['password'];
                $retypepassword = $_POST['password2'];
    
                $usernameExists = checkUsername($username);
                if($usernameExists) {
                    $errorMessage2 = "Username already exists!";
                    include '../views/user/signup.php';
                    exit();
                }
                $isValid = addUser($username, $password, $retypepassword);

                if($isValid === -3) {
                    $errorMessage2 = "Username cannot be an email!";
                    include '../views/user/signup.php';
                    exit();
                }

                if($isValid === -2) {
                    $errorMessage2 = "Weak password! (must be at least 10 characters long and contain at least one special character)";
                    include '../views/user/signup.php';
                    exit();
                }
    
                if($isValid === -1) {
                    $errorMessage2 = "Passwords do not match!";
                    include '../views/user/signup.php';
                    exit();
                }
    
                if($isValid) {
                    include '../views/user/personalization1.php';
                    exit();
                } else {
                    $errorMessage2 = "Username already exists!";
                    include '../views/user/signup.php';
                    exit();
                }

            //first add preferences page (email, first name, last name, age, gender set)
            case 'pref1':
                $email = $_POST['email'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $age = $_POST['age'];
                $gender = $_POST['gender'];
                $username = $_SESSION['username'];

                $existEmail = checkEmail($email);
                if($existEmail) {
                    $errorMessage3 = "Email already exists!";
                    include '../views/user/personalization1.php';
                    exit();
                }

                $isValid = addPref1($username, $email, $firstName, $lastName, $age, $gender);
                if($isValid === -1) {
                    $errorMessage3 = "Invalid email format!";
                    include '../views/user/personalization1.php';
                    exit();
                }

                if($isValid) {
                    include '../views/user/personalization2.html';
                    exit();
                } else {
                    $errorMessage3 = "Invalid input!";
                    include '../views/user/personalization1.html';
                    exit();
                }

            //second add preferences page(add to database table user_preferences from a selection of checkboxes)
            case 'pref2':
                $preferences = $_POST['preferences'];
                $username = $_SESSION['username'];
                $id = getID($username);

                foreach ($preferences as $preference) {
                    addPreference($id, $preference);
                }

                include '../views/user/homepage.php';
                exit();

            //homepage actions
            case 'addToShoppingList':
                
                include '../views/user/homepage.php';
                exit();
                

            //Preferences managing page
            case 'modifyPref':
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
    
                include '../views/user/preferinte.php';
                exit();

            //profile page
            case 'deleteAcc':
                deleteUser($_SESSION['username']);
                if (isset($_COOKIE["rememberme"])) {
                    setcookie("rememberme", "", time() - 3600);
                }
                session_destroy();
                include '../views/user/index.html';
                exit();

            case 'logout':
                if (isset($_COOKIE["rememberme"])) {
                    setcookie("rememberme", "", time() - 3600);
                }
                session_destroy();
                include '../views/user/index.html';
                exit();
            case 'resetPass':
                $oldpassword = $_POST['oldPassword'];
                $newpassword = $_POST['newPassword'];
                $newpasswordrepeat = $_POST['newPasswordRepeat'];
                $control = resetPassword($oldpassword, $newpassword, $newpasswordrepeat);
                if($control){
                    if(isset($_COOKIE["rememberme"])){
                        include '../views/user/homepage.php';
                        exit();
                    }
                    else{
                        include '../views/user/login.php';
                        exit();
                    }
                } else {
                    if($control === -2){
                        $errorMessage4 = "Passwords do not match!";
                        include '../views/user/reset-password.php';
                        exit();
                    }
                    if($control === -1){
                        $errorMessage4 = "Invalid old password!";
                        include '../views/user/reset-password.php';
                        exit();
                    }
                    if($control === -3){
                        $errorMessage4 = "Weak password! (must be at least 10 characters long and contain at least one special character)";
                        include '../views/user/reset-password.php';
                        exit();
                    }
                    if($control){
                        include '../views/user/homepage.php';
                        exit();
                    }
                }
        }
    }
}
else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['action'])){
        $action = $_GET['action'];
        switch($action){
            case 'redirectResetPass':
                include '../views/user/reset-password.php';
                exit();
            //redirect to signup page
            case 'redirectAcc':
                $user_data = getUserInfo($_SESSION['username']);
                include '../views/user/account.php';
                exit();
            //redirect to preferences page
            case 'redirectPrefs':
                $preferences = getUserPreferences($_SESSION['id']);
                include '../views/user/preferinte.php';
                exit();
            //redirect to homepage
            case 'redirectHome':
                include '../views/user/homepage.php';
                exit();
            //redirect to signup page
            case 'signup':
                include '../views/user/signup.php';
                exit();
            //redirect to login page (or homepage if rememberme cookie is set)
            case 'login':
                if(isset($_COOKIE["rememberme"])){
                    include '../views/user/homepage.php';
                    exit();
                }
                else{
                    include '../views/user/login.php';
                    exit();
                }
        }
    }
    else{
        //show index page by default
        include '../views/user/index.html';
    }
}

?>
