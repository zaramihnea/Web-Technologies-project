<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/user/stylesheets/homepage.css">
    <link rel="stylesheet" href="../views/user/responsive/homepage.css">
    <title>Home</title>
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
    <section class="header">
        <h1 class="header--title">
            Welcome to Culinary Preferences Organizer, <?php if(isset($_SESSION['username'])) { echo $_SESSION['username']; } ?>!</h1>
        </h1>
    </section>
    <section class="content">
        <div class="split">
            <div class="left">
                <h1 class="content--title">
                    Products based on your preferences:
                </h1>
                <div class="content--box"></div>
            </div>
            <div class="right">
                <h1 class="content--title">
                    Shopping list:
                </h1>
                <div class="content--box">
                   <?php 
                        if(isset($shoppingList)){
                            foreach($shoppingList as $item){
                                echo "<p class='shoppingList'>".$item['item_name']."</p>";
                            }
                        }
                   ?>
                </div>
        </div>
    </section>
    <form method="post">
        <input type="hidden" name="action" value="addToShoppingList">
        <input class="shoppingList" type="text" name="item" placeholder="Add one item to shopping list">
    </form>
    <script src="../views/user/scripts/homepage.js"></script>
</body>
</html>