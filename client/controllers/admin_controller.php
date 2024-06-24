<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../models/admin_model.php';

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
    
                $isValid = checkAdminCredentials($username, $password);
    
                if($isValid) {
                    if(isset($_POST["rememberme"])) {
                        setcookie("rememberme", $username, time()+(60*60*24*1));
                    }

                    $names = getPreferences();
                    $counter = getPreferenceUsage();
                    $users = getUsers();

                    include '../views/admin/dashboard.php';
                    exit();
                } else {
                    $errorMessage = "Invalid username or password!";
                    include '../views/admin/login.php';
                    exit();
                }
            //delete User    
            case 'deleteUser':
                $userId = $_POST['user_id'];
    
                deleteUser($userId);

                $names = getPreferences();
                $counter = getPreferenceUsage();
                $users = getUsers();
    
                include '../views/admin/dashboard.php'; 
                break;
            case 'exportToCSV':
                $table = $_POST['table'];
                exportTableToCSV($table);

                $names = getPreferences();
                $counter = getPreferenceUsage();
                $users = getUsers();
    
                include '../views/admin/dashboard.php'; 
                break;
            case 'exportToPDF':
                $table = $_POST['table'];
                exportTableToPDF($table);

                $names = getPreferences();
                $counter = getPreferenceUsage();
                $users = getUsers();
    
                include '../views/admin/dashboard.php';       
                break;
            case 'exportStats':
                $names = getPreferences();
                $counter = getPreferenceUsage();
                $users = getUsers();

                if (isset($_POST['export_csv'])) {
                    exportStatsToCSV($names, $counter, count($users));
                } elseif (isset($_POST['export_pdf'])) {
                    exportStatsToPDF($names, $counter, count($users));
                }
                break;   
            case 'exportToSQL':
                exportDatabaseToSQL();
                break;
            case 'logout':
                if (isset($_COOKIE["rememberme"])) {
                    setcookie("rememberme", "", time() - 3600);
                }
                session_destroy();
                header('Location: ../controllers/culinary_controller.php');
                exit();    
        }
    }
} 
else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(isset($_GET['action'])){
        $action = $_GET['action'];
        switch($action){
            case 'dashboard': 
                echo 'dsa';
                include '../views/admin/dashboard.php';
                exit();
            default:
                include '../views/admin/login.php';
                exit();
        }
    } else {
        include '../views/admin/login.php';
    }
}



?>
