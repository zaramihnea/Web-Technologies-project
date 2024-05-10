<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/stylesheets/homepage.css">
    <title>Home</title>
</head>
<body>
    <nav class="navbar">
        <a href=" ../views/homepage.php"><img src="../views/icons/home_app_logo.svg" alt="Home button" ></a>
        <ul class="navbar--buttons">
            <li class="navbar--button"><a href="../controllers/culinary_controller.php?action=redirectHome">Home</a></li>
            <li class="navbar--button"><a href="../controllers/culinary_controller.php?action=redirectPrefs">Preferences</a></li>
            <li class="navbar--button"><a href=" ../controllers/culinary_controller.php?action=redirectAcc"><img src="../views/icons/account_circle.svg" alt="Account icon"></a></li>
        </ul>
    </nav>
    <section class="header">
        <h1 class="header--title">
            Welcome to Culinary Preferences Organizer, <?php if(isset($_SESSION['username'])) { echo $_SESSION['username']; } ?>!</h1>
        </h1>
    </section>
    <section class="content">
        <h1 class="content--title">
            Dishes based on your preferences:
        </h1>
        <div class="content--box"></div>
    </section>
    <input class="shoppingList">
    <script src="../views/scripts/homepage.js"></script>
</body>
</html>