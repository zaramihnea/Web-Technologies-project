<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/user/stylesheets/account.css">
    <link rel="stylesheet" href="../views/user/responsive/account.css">
    <title>Account</title>
</head>
<body>
    <nav class="navbar">
        <img id="logo" src="../views/user/icons/logo.jpeg" alt="Logo" >
        <ul class="navbar--buttons">
            <li class="navbar--button"><a class= "button" href="../controllers/culinary_controller.php?action=redirectHome">Home</a></li>
            <li class="navbar--button"><a class= "button" href="../controllers/culinary_controller.php?action=redirectPrefs">Preferences</a></li>
            <li class="navbar--button"><a class= "button" href=" ../controllers/culinary_controller.php?action=redirectAcc"><img class="myAccount" src="../views/user/icons/account-circle.png" alt="Account icon"/></a></li>
        </ul>
    </nav>
    <section class="content">
    <div class="left-section">
        <div class="content--username">
            <label for="username">Username:</label>
            <input type="text" id="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
        <div class="content--email">
            <label for="email">Email:</label> 
            <input type="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
        </div>
        <div class="content--first-name">
            <label for="first-name">First name:</label>
            <input type="text" id="first-name" value="<?php echo htmlspecialchars($user_data['fname']); ?>" readonly>
        </div>
        <div class="content--last-name">
            <label for="last-name">Last name:</label>
            <input type="text" id="last-name"  value="<?php echo htmlspecialchars($user_data['lname']); ?>" readonly>
        </div>
        <div class="content--age">
            <label for="age">Age:</label>
            <input type="text" id="age" value="<?php echo htmlspecialchars($user_data['age']); ?>" readonly>
        </div>
        <div class="content--gender">
            <label for="gender">Gender:</label>
            <input type="text" id="gender" value="<?php 
                switch ($user_data['gender']) {
                        case 0:
                            echo 'female';
                            break;
                        case 1:
                            echo 'male';
                            break;
                        case 2:
                            echo 'other';
                            break;
                        default:
                            echo 'unspecified';
                            break;
                    } 
            ?>" readonly>
        </div>
    </div>
    <div class="right-section">
        <div class="content--delete">
            <label for="delete">Delete account:</label>
            <button id="delete" onclick="openModal()">Delete</button>
        </div>
        <div class="content--logout">
            <label for="logout">Logout:</label>
            <form action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="logout">
                <button type="submit" name="Logout" id="logout">Logout</button>
            </form>
        </div>
        <div class="reset-password">
            <label for="reset">Reset password:</label>
            <button id="reset" onclick="location.href='../controllers/culinary_controller.php?action=redirectResetPass'">Reset</button>
        </div>
    </div>
</section>
<!-- Modal -->
<div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Are you sure you want to delete your account?</p>
            <form action="culinary_controller.php" method="post">
                <input type="hidden" name="action" value="deleteAcc">
                <button type="submit" name="confirmDelete">Yes, delete</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("deleteModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("deleteModal").style.display = "none";
        }
    </script>

</body>
</html>