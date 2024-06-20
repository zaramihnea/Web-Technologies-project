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
            <button type="button" id="deleteCircle" class="circle-button">-</button>
            <button type="submit" id="savePreferences" class="circle-button">Save</button>
            <button type="button" id="addCircle" class="circle-button">+</button>
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
            <select id="newPreference" name="newPreference" required>
            </select>
            <button type="submit" class="circle-button">Add</button>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("addCircle");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        fetchAvailablePreferences();
        modal.style.display = "block";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }
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

    function fetchAvailablePreferences() {
        fetch('controller.php?action=getAvailablePreferences')
            .then(response => response.json())
            .then(data => {
                const newPreferenceSelect = document.getElementById('newPreference');
                newPreferenceSelect.innerHTML = '';
                data.forEach(preference => {
                    const option = document.createElement('option');
                    option.value = preference;
                    option.textContent = preference;
                    newPreferenceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching available preferences:', error));
    }
</script>
</body>
</html>
