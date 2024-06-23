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
    <a href="../controllers/culinary_controller.php?action=redirectHome"><img id="logo" src="../views/user/icons/logo.jpeg" alt="Logo"></a>
    <ul class="navbar--buttons">
        <li class="navbar--button"><a class="button" href="../controllers/culinary_controller.php?action=redirectPrefs">Preferences</a></li>
        <li class="navbar--button"><a class="button" href="../controllers/culinary_controller.php?action=redirectAcc"><img class="myAccount" src="../views/user/icons/account-circle.png" alt="Account icon"/></a></li>
    </ul>
</nav>
<section class="content">
    <h2>View and edit your preferences:</h2>
    
    <!-- Form for Deleting Preferences -->
    <form id="deletePreferencesForm" action="culinary_controller.php" method="post">
        <input type="hidden" name="action" value="deletePreferences">
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
            <button type="submit" id="deleteCircle" class="circle-button">-</button>
        </div>
    </form>

    <!-- Button to Open the Modal -->
    <button id="addCircle" class="circle-button">+</button>
    
    <!-- The Modal for Adding Preferences -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Preference</h2>
            
            <!-- Form for Adding Preferences -->
            <form id="addPreferencesForm" action="culinary_controller.php" method="post">
                <input type="hidden" name="action" value="addPreference">
                <select id="newPreference" name="newPreference">
                    <!-- Options will be populated by PHP -->
                    <?php
                    $availablePreferences = getAllPreferences();
                    foreach ($availablePreferences as $preference) {
                        echo '<option value="' . htmlspecialchars($preference) . '">' . htmlspecialchars($preference) . '</option>';
                    }
                    ?>
                </select>
                <button type="submit">Add Preference</button>
            </form>
        </div>
    </div>
</section>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    // Get the button that opens the modal
    var btn = document.getElementById("addCircle");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    
    // When the user clicks on the button, open the modal
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
</script>
</body>
</html>
