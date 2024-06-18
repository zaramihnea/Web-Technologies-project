<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../views/user/stylesheets/preferinte.css">
    <title>Preferinte</title>
</head>
<body>
<nav class="navbar">
        <a  href="../controllers/culinary_controller.php?action=redirectHome"><img id="logo" src="../views/user/icons/logo.jpeg" alt="Logo" ></a>
        <ul class="navbar--buttons">
            <li class="navbar--button"><a class= "button" href="../controllers/culinary_controller.php?action=redirectPrefs">Preferences</a></li>
            <li class="navbar--button"><a class= "button" href=" ../controllers/culinary_controller.php?action=redirectAcc"><img class="myAccount" src="../views/user/icons/account-circle.png" alt="Account icon"/></a></li>
        </ul>
    </nav>
    <section class="content">
        <h2>View and edit your preferences:</h2>
        <form id="preferencesForm" action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="modifyPref">
            <input type="hidden" id="preferencesToDelete" name="preferencesToDelete" value="">
            <div class="favorite-foods" id="favoriteFoods">
                <?php
                if (isset($preferences) && is_array($preferences)) {
                    foreach ($preferences as $preference) {
                        echo '<input type="checkbox" id="' . htmlspecialchars($preference) . '" name="preference[]" value="' . htmlspecialchars($preference) . '">';
                        echo '<label for="' . htmlspecialchars($preference) . '">' . htmlspecialchars($preference) . '</label>';
                    }
                } else {
                    echo 'No preferences found.';
                }
                ?> 
            </div>
            <p style="color:green"><?php if (isset($msg)) { echo $msg; } ?></p>
            <div class="button-container">
                <button type="button" id="deleteCircle">-</button>
                <button type="submit" id="savePreferences">Save</button>
                <button type="button" id="addCircle">+</button>
            </div>
        </form>
</section>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Preference</h2>
        <form id="addPreferenceForm" action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="addPref">
            <label for="newPreference">New Preference:</label>
            <input type="text" id="newPreference" name="newPreference" required>
            <button type="submit">Add</button>
        </form>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("addCircle");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    var removeButton = document.getElementById("deleteCircle");
    var preferencesToDelete = document.getElementById("preferencesToDelete");

    removeButton.addEventListener("click", function(event) {
        event.preventDefault();
        console.log("Remove button pressed");
        var checkedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        checkedCheckboxes.forEach(function(checkbox) {
            preferencesToDelete.value += (preferencesToDelete.value ? ',' : '') + checkbox.value;
            checkbox.remove();
            var label = document.querySelector('label[for="' + checkbox.id + '"]');
            label.remove();
        });
    });
</script>
</body>
</html>
