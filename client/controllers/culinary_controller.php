<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);
require_once "../models/culinary_model.php";

session_start();

//POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        $action = $_POST["action"];
        switch ($action) {
            //login page
            case "checkCred":
                $username = $_POST["user_name"];
                $password = $_POST["password"];
                $result = login($username, $password);

                if ($result['responseCode'] === 200) {
                    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                        $username = getUsernameFromEmail($username);
                    }
                    $_SESSION["username"] = $username;
                    $user_id = getID($username);
                    $_SESSION["id"] = $user_id;
                    if (isset($_POST["rememberme"])) {
                        setcookie(
                            "rememberme",
                            $username,
                            time() + 60 * 60 * 24 * 1
                        );
                    }
                    $suggestions = getUserFoodsService($user_id);
                    include "../views/user/homepage.php";
                } else {
                    $errorMessage = $result['responseData']['error'] ?? "An error occurred!";
                    include "../views/user/login.php";
                }
                exit();

            //signup page
            case "addCred":
                $username = $_POST["user_name"];
                $password = $_POST["password"];
                $retypepassword = $_POST["password2"];
                $result = signup($username, $password, $retypepassword);

                if ($result['responseCode'] === 200) {
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = getID($username);
                    include "../views/user/signup_details.php";
                } else {
                    $errorMessage2 = $result['responseData']['error'] ?? "An error occurred!";
                    include "../views/user/signup.php";
                }
                exit();

            //user details page(email, first name, last name, age, gender set)
            case "pref1":
                $email = $_POST["email"];
                $firstName = $_POST["firstName"];
                $lastName = $_POST["lastName"];
                $age = $_POST["age"];
                $gender = $_POST["gender"];
                $username = $_SESSION["username"];

                $control1 = checkEmail($email);

                if ($control1['responseData']['exists'] === true) {
                    $errorMessage3 = "Email already exists!";
                    include "../views/user/signup_details.php";
                    exit();
                }

                $control = addDetails(
                    $username,
                    $email,
                    $firstName,
                    $lastName,
                    $age,
                    $gender
                );

                if ($control['responseCode'] === 200) {
                    include "../views/user/signup_preferences.html";
                } else {
                    $errorMessage3 = $control['responseData']['error'] ?? "An error occurred!";
                    include "../views/user/signup_details.php";
                }
                exit();

            //"first preferences" page(add to database table user_preferences from a selection of checkboxes)
            case "pref2":
                $preferences = $_POST["preferences"];
                $username = $_SESSION["username"];
                $id = getID($username);

                $control = addPreferences($id, $preferences);
                if ($control['responseCode'] === 200) {
                    $suggestions = getUserFoodsService($user_id);
                    include "../views/user/homepage.php";
                } else {
                    $errorMessage3 = $control['responseData']['error'] ?? "An error occurred!";
                    include "../views/user/signup_preferences.html";
                }
                exit();

            //homepage actions
            case "addToShoppingList":
                $user_id = $_SESSION["id"];
                $item = trim($_POST["item"]);
                if (empty($item)) {
                    $errorMessage = "Item cannot be empty!";
                    $shoppingList = getItemsFromShoppingList($user_id);
                    include "../views/user/homepage.php";
                    exit();
                }
                $check = checkIfItemExists($user_id, $item);
                if ($check) {
                    $result = addOneToQuantity($user_id, $item);
                    $shoppingList = getItemsFromShoppingList($user_id);
                } else {
                    addToShoppingList($user_id, $item, 1);
                    $shoppingList = getItemsFromShoppingList($user_id);
                }
                $suggestions = getUserFoodsService($user_id);
                include "../views/user/homepage.php";
                exit();

            case "deleteFromShoppingList":
                $user_id = $_SESSION["id"];
                $item = $_POST["item"];
                $result = removeFromShoppingList($user_id, $item);
                $shoppingList = getItemsFromShoppingList($user_id);
                $suggestions = getUserFoodsService($user_id);
                include "../views/user/homepage.php";
                exit();

            //Preferences managing page
            case "addPref":
                if (isset($_POST['newPreference'])) {
                    $preference = trim($_POST['newPreference']);
                    $user_id = $_SESSION["id"];
                    $oldPreferences = getUserPreferences($user_id);
                    $oldPreferencesArray = array_column($oldPreferences, 'preference');
                    if (in_array($preference, $oldPreferencesArray)) {
                        $msg = 'Preference already exists.';
                    } else {
                        $addResponse = addPreference($user_id, $preference);
                        if ($addResponse['responseCode'] === 201) {
                            $msg = 'Preference added successfully!';
                        } else {
                            $msg = $addResponse['responseData']['error'] ?? 'Failed to add preference.';
                        }
                    }
                } else {
                    $msg = 'No preference provided.';
                }
                $preferences = getUserPreferences($user_id);
                include "../views/user/preferinte.php";
                exit();

            // Handle deleting preferences
            case "modifyPref":
                $preferencesToDelete = isset($_POST['preferencesToDelete']) ? explode(',', $_POST['preferencesToDelete']) : [];
                $user_id = $_SESSION["id"];
                $oldPreferences = getUserPreferences($user_id);
                $oldPreferencesArray = array_column($oldPreferences, 'preference');
                $preferencesToRemove = array_intersect($oldPreferencesArray, $preferencesToDelete);
                foreach ($preferencesToRemove as $preference) {
                    $preferenceData = getPrefID($preference, $user_id);
                    if ($preferenceData && isset($preferenceData['responseCode']) && $preferenceData['responseCode'] === 200) {
                        $deleteResponse = deletePreference($preferenceData['id']);
                        if ($deleteResponse['responseCode'] !== 200) {
                            echo "Failed to delete preference: " . htmlspecialchars($preferenceData['id']);
                        }
                    } else {
                        echo "Preference ID not found for: " . htmlspecialchars($preference);
                        error_log("Preference ID not found for: " . htmlspecialchars($preference) . " with response: " . print_r($preferenceData, true));
                    }
                }
                $preferences = getUserPreferences($user_id);
                $msg = "Preferences updated successfully!";
                include "../views/user/preferinte.php";
                exit();

            case "getAvailablePreferences":
                $user_id = $_SESSION["id"];
                $userPreferences = getUserPreferences($user_id);
                $allPreferences = getAllPreferences();
                $availablePreferences = array_diff($allPreferences, array_column($userPreferences, 'preference'));
                echo json_encode($availablePreferences);
                exit();


            //profile page
            case "deleteAcc":
                deleteUser($_SESSION["id"]);
                if (isset($_COOKIE["rememberme"])) {
                    setcookie("rememberme", "", time() - 3600);
                }
                session_destroy();
                include "../views/user/index.html";
                exit();

            case "logout":
                if (isset($_COOKIE["rememberme"])) {
                    setcookie("rememberme", "", time() - 3600);
                }
                session_destroy();
                include "../views/user/index.html";
                exit();
            case "resetPass":
                $oldpassword = $_POST["oldPassword"];
                $newpassword = $_POST["newPassword"];
                $newpasswordrepeat = $_POST["newPasswordRepeat"];
                $control = resetPassword(
                    $oldpassword,
                    $newpassword,
                    $newpasswordrepeat
                );
                if ($control) {
                    if (isset($_COOKIE["rememberme"])) {
                        $suggestions = getUserFoodsService($user_id);
                        include "../views/user/homepage.php";
                        exit();
                    } else {
                        include "../views/user/login.php";
                        exit();
                    }
                } else {
                    if ($control === -2) {
                        $errorMessage4 = "Passwords do not match!";
                        include "../views/user/reset-password.php";
                        exit();
                    }
                    if ($control === -1) {
                        $errorMessage4 = "Invalid old password!";
                        include "../views/user/reset-password.php";
                        exit();
                    }
                    if ($control === -3) {
                        $errorMessage4 =
                            "Weak password! (must be at least 10 characters long and contain at least one special character)";
                        include "../views/user/reset-password.php";
                        exit();
                    }
                    if ($control) {
                        $suggestions = getUserFoodsService($user_id);
                        include "../views/user/homepage.php";
                        exit();
                    }
                }
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["action"])) {
        $action = $_GET["action"];
        switch ($action) {
            case "redirectResetPass":
                include "../views/user/reset-password.php";
                exit();
            //redirect to signup page
            case "redirectAcc":
                $user_data = getUserInfo($_SESSION["id"]);
                include "../views/user/account.php";
                exit();
            //redirect to preferences page
            case "redirectPrefs":
                $preferences = getUserPreferences($_SESSION["id"]);
                include "../views/user/preferinte.php";
                exit();
            //redirect to homepage
            case "redirectHome":
                $shoppingList = getItemsFromShoppingList($_SESSION["id"]);
                $suggestions = getUserFoodsService($_SESSION["id"]);
                include "../views/user/homepage.php";
                exit();
            //redirect to signup page
            case "signup":
                include "../views/user/signup.php";
                exit();
            //redirect to login page (or homepage if rememberme cookie is set)
            case "login":
                if (isset($_COOKIE["rememberme"])) {
                    $shoppingList = getItemsFromShoppingList($_SESSION["id"]);
                    $suggestions = getUserFoodsService($_SESSION["id"]);
                    include "../views/user/homepage.php";
                    exit();
                } else {
                    include "../views/user/login.php";
                    exit();
                }
        }
    } else {
        //show index page by default
        include "../views/user/index.html";
    }
}

?>