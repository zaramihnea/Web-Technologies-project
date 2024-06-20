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
        <a class="button" href="../controllers/culinary_controller.php?action=redirectHome">
            <img id="logo" src="../views/user/icons/logo.jpeg" alt="Logo">
        </a>
        <div class="menu-icon" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>
        <ul class="navbar--buttons" id="nav-menu">
            <li class="navbar--button"><a class="button" href="../controllers/culinary_controller.php?action=redirectPrefs">Preferences</a></li>
            <li class="navbar--button"><a class="button" href="../controllers/culinary_controller.php?action=redirectAcc">
                <img class="myAccount" src="../views/user/icons/account-circle.png" alt="Account icon"/>
            </a></li>
        </ul>
    </nav>
    <section class="header">
        <h1 class="header--title">
            Welcome to Culinary Preferences Organizer, <?php if(isset($_SESSION['username'])) { echo htmlspecialchars($_SESSION['username']); } ?>!
        </h1>
    </section>
    <section class="content">
        <div class="left">
            <h1 class="content--title">
                Products based on your preferences:
            </h1>
            <div class="content--box">
                <?php foreach ($suggestions as $index => $suggestion): ?>
                    <div class="content--box--inner">
                        <img src="<?= htmlspecialchars($suggestion['image_url'] ?? '') ?>" alt="product image">
                        <button class="overlay-button"
                            data-name="<?= htmlspecialchars($suggestion['product_name'] ?? 'No name available') ?>"
                            data-image="<?= htmlspecialchars($suggestion['image_url'] ?? '') ?>"
                            data-keywords="<?= htmlspecialchars(json_encode($suggestion['_keywords'] ?? [])) ?>"
                            data-categories="<?= htmlspecialchars(json_encode(explode(',', $suggestion['categories'] ?? ''))) ?>">Click for details</button>
                    
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="right">
            <h1 class="content--title">
                Shopping list:
            </h1>
            <div class="content--box">
                <?php if (isset($successMessage)): ?>
                    <p class="successMessage"><?= htmlspecialchars($successMessage) ?></p>
                <?php endif; ?>
                <?php if (isset($errorMessage)): ?>
                    <p class="errorMessage"><?= htmlspecialchars($errorMessage) ?></p>
                <?php endif; ?>
                <?php if (isset($shoppingList)): ?>
                    <?php foreach ($shoppingList as $item): ?>
                        <div class="shoppingList--item">
                            <span class="item-name"><?= htmlspecialchars($item['item_name']) ?></span>
                            <span class="item-quantity"><?= htmlspecialchars($item['quantity']) ?></span>
                            <form method="post" action="../controllers/culinary_controller.php" class="delete-form">
                                <input type="hidden" name="action" value="deleteFromShoppingList">
                                <input type="hidden" name="item" value="<?= htmlspecialchars($item['item_name']) ?>">
                                <button type="submit" class="delete-button"><img src="../views/user/icons/delete.png" alt="Delete"></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <form action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="addToShoppingList">
            <input class="shoppingList" type="text" name="item" placeholder="Add one item to shopping list">
        </form>
    </section>

    <button id="open-shopping-list" class="open-shopping-list">+</button>
    
    <div id="shopping-list-modal" class="shopping-list-modal">
        <div class="shopping-list-modal-content">
            <span class="shopping-list-close">&times;</span>
            <div class="content--box">
                <?php if (isset($successMessage)): ?>
                    <p class="successMessage"><?= htmlspecialchars($successMessage) ?></p>
                <?php endif; ?>
                <?php if (isset($errorMessage)): ?>
                    <p class="errorMessage"><?= htmlspecialchars($errorMessage) ?></p>
                <?php endif; ?>
                <?php if (isset($shoppingList)): ?>
                    <?php foreach ($shoppingList as $item): ?>
                        <div class="shoppingList--item">
                            <span class="item-name"><?= htmlspecialchars($item['item_name']) ?></span>
                            <span class="item-quantity"><?= htmlspecialchars($item['quantity']) ?></span>
                            <form method="post" action="../controllers/culinary_controller.php" class="delete-form">
                                <input type="hidden" name="action" value="deleteFromShoppingList">
                                <input type="hidden" name="item" value="<?= htmlspecialchars($item['item_name']) ?>">
                                <button type="submit" class="delete-button"><img src="../views/user/icons/delete.png" alt="Delete"></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="overlay" class="overlay-container">
        <div class="overlay-content">
            <span class="close-button">&times;</span>
            <img id="overlay-image" src="" alt="Product image" style="max-width: 100%; max-height: 300px;">
            <p id="overlay-name"></p>
            <p id="overlay-keywords"></p>
            <p id="overlay-categories"></p>
            <form id="add-to-list-form" action="culinary_controller.php" method="post">
                <input type="hidden" name="action" value="addToShoppingList">
                <input type="hidden" id="item-name-input" name="item">
                <button type="submit" class="add-to-list-button">Add to Shopping List</button>
            </form>
        </div>
    </div>

    <script>
         function toggleMenu() {
            const navMenu = document.getElementById('nav-menu');
            navMenu.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('overlay');
            const overlayImage = document.getElementById('overlay-image');
            const overlayName = document.getElementById('overlay-name');
            const overlayKeywords = document.getElementById('overlay-keywords');
            const overlayCategories = document.getElementById('overlay-categories');
            const itemNameInput = document.getElementById('item-name-input');
            const closeButton = document.querySelector('.close-button');
            const openShoppingListButton = document.getElementById('open-shopping-list');
            const shoppingListModal = document.getElementById('shopping-list-modal');
            const shoppingListCloseButton = document.querySelector('.shopping-list-close');

            document.querySelectorAll('.overlay-button').forEach(button => {
                button.addEventListener('click', () => {
                    const name = button.getAttribute('data-name') || 'No name available';
                    const image = button.getAttribute('data-image') || '';
                    const keywordsData = button.getAttribute('data-keywords') || '[]';
                    const categoriesData = button.getAttribute('data-categories') || '[]';

                    console.log("Name:", name);
                    console.log("Image:", image);
                    console.log("Keywords Data:", keywordsData);
                    console.log("Categories Data:", categoriesData);

                    const keywords = JSON.parse(keywordsData);
                    const categories = JSON.parse(categoriesData);

                    console.log("Parsed Keywords:", keywords);
                    console.log("Parsed Categories:", categories);

                    overlayImage.src = image || 'default-image-url.jpg';
                    overlayName.textContent = name;
                    overlayKeywords.textContent = 'Keywords: ' + (keywords.length ? keywords.join(', ') : 'No keywords available');
                    overlayCategories.textContent = 'Categories: ' + (categories.length ? categories.join(', ') : 'No categories available');
                    itemNameInput.value = name;

                    overlay.style.display = 'flex';
                });
            });

            closeButton.addEventListener('click', () => {
                overlay.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target === overlay) {
                    overlay.style.display = 'none';
                }
            });

            openShoppingListButton.addEventListener('click', () => {
                shoppingListModal.style.display = 'flex';
            });

            shoppingListCloseButton.addEventListener('click', () => {
                shoppingListModal.style.display = 'none';
            });

            window.addEventListener('click', (event) => {
                if (event.target === shoppingListModal) {
                    shoppingListModal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
