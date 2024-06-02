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
                        if (isset($successMessage)) {
                            echo "<p class='successMessage'>" . htmlspecialchars($successMessage) . "</p>";
                        }
                        if (isset($errorMessage)) {
                            echo "<p class='errorMessage'>" . htmlspecialchars($errorMessage) . "</p>";
                        }
                        if (isset($shoppingList)) {
                            foreach ($shoppingList as $item) {
                                echo '<div class="shoppingList--item">';
                                echo '<span class="item-name">' . htmlspecialchars($item['item_name']) . '</span>';
                                echo '<span class="item-quantity">' . htmlspecialchars($item['quantity']) . '</span>';
                                echo '<form method="post" action="../controllers/culinary_controller.php" class="delete-form">';
                                echo '<input type="hidden" name="action" value="deleteFromShoppingList">';
                                echo '<input type="hidden" name="item" value="' . htmlspecialchars($item['item_name']) . '">';
                                echo '<button type="submit" class="delete-button"><img src="../views/user/icons/delete.png" alt="Delete"></button>';
                                echo '</form>';
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
        </div>
    </section>
    <form action="culinary_controller.php" method="post">
        <input type="hidden" name="action" value="addToShoppingList">
        <input class="shoppingList" type="text" name="item" placeholder="Add one item to shopping list">
    </form>
    <script src="../views/user/scripts/homepage.js"></script>
</body>
</html>