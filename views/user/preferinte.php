<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/user/stylesheets/preferinte.css">
    <link rel="stylesheet" href="../views/user/responsive/preferinte.css">
    <title>Preferinte</title>
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
        <h2>View and edit your preferences:</h2>
        <form id="preferencesForm" action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="modifyPref">
            <div class="favorite-foods" id="favoriteFoods">
                <?php
                    foreach ($preferences as $preference) {
                        echo '<input type="checkbox" id="' . $preference . '" name="preference[]" value="' . $preference . '">';
                        echo '<label for="' . $preference . '">' . $preference . '</label>';
                    }
                ?> 
            </div>
            <p style="color:green"><?php if(isset($msg)) { echo $msg; } ?></p>
            <div class="button-container">
                <button type="button" id="deleteCircle">-</button>
                <button type="submit" id="savePreferences">Save</button>
                <button type="button" id="addCircle">+</button>
            </div>
        </form>
    </section>

    

    <script>
        var addButton = document.getElementById("addCircle");
        var removeButton = document.getElementById("deleteCircle");

        addButton.addEventListener("click", function() {
            event.preventDefault();
            console.log("Add button pressed");
            var favoriteFoods = document.getElementById("favoriteFoods");

            var newCheckbox = document.createElement("input");
            newCheckbox.type = "checkbox";
            newCheckbox.id = "food" + (favoriteFoods.children.length + 1);
            newCheckbox.name = "food" + (favoriteFoods.children.length + 1);
            newCheckbox.value = "New food";

            var newLabel = document.createElement("label");
            newLabel.htmlFor = newCheckbox.id;
            newLabel.textContent = "New food";
            
            favoriteFoods.appendChild(newCheckbox);
            favoriteFoods.appendChild(newLabel);
        });

        removeButton.addEventListener("click", function() {
            event.preventDefault();
            console.log("Remove button pressed");
            var checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            checkedCheckboxes.forEach(function(checkbox) {
                checkbox.remove();
                var label = document.querySelector('label[for="' + checkbox.id + '"]');
                label.remove();
            });
        });
    </script>
</body>
</html>