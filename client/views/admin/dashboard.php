<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/admin/responsive/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../views/admin/stylesheets/dashboard.css">
    <title>Admin Dashboard</title>
    <script src="../views/admin/scripts/dashboard.js"></script>
  </head>
  <body>
    <h1>Dashboard</h1>
    <section class="content">
      <table class="stats">
        <caption>Stats</caption>
        <tr>
          <th>No. of Users</th>
          <?php foreach ($names as $header) {
              echo "<th>" . htmlspecialchars($header) . "</th>";
          } ?>
        </tr>
        <tr>
          <?php 
            echo "<td>". htmlspecialchars(count($users)) . "</td>";
            foreach ($counter as $info) {
              echo "<td>". htmlspecialchars($info) . "</td>";
          } ?>
        </tr>
      </table>
      <table>
        <caption>Users</caption>
        <tr>
          <th>id</th>
          <th>Username</th>
          <th>Email</th>
          <th>First name</th>
          <th>Last name</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Banned</th>
          <th>Time of ban</th>
          <th>Ban user</th>
          <th>Delete user</th>
        </tr>
        <?php
          foreach ($users as $user) {
            echo "<tr>";
              echo "<td>" . htmlspecialchars($user['user_id']) . "</td>";
              echo "<td>" . htmlspecialchars($user['username']) . "</td>";
              echo "<td>" . htmlspecialchars($user['email']) . "</td>";
              echo "<td>" . htmlspecialchars($user['fname']) . "</td>";
              echo "<td>" . htmlspecialchars($user['lname']) . "</td>";
              echo "<td>" . htmlspecialchars($user['age']) . "</td>";
              echo "<td>" . htmlspecialchars($user['gender'] == 1 ? "male" : "female") . "</td>";
              echo "<td>" . htmlspecialchars($user['lname']) . "</td>";
              echo "<td>" . htmlspecialchars($user['age']) . "</td>";
              //Ban user button
              echo "<td>
                      <form action='admin_controller.php' method='post'>
                        <input type='hidden' name='action' value='banUser'> 
                        <input type='hidden' name='user_id' value='" . htmlspecialchars($user['user_id']) . "'> 
                        <button type='submit'>
                          <img src='../views/admin/icons/ban.svg' alt='Ban'>
                        </button>
                      </form>
                    </td>";
              //Delete user button
              echo "<td>
                      <form action='admin_controller.php' method='post'>
                        <input type='hidden' name='action' value='deleteUser'> 
                        <input type='hidden' name='user_id' value='" . htmlspecialchars($user['user_id']) . "'> 
                        <button type='submit'>
                          <img src='../views/admin/icons/delete.svg' alt='Delete'>
                        </button>
                      </form>
                    </td>";
            echo "</tr>";
          }
        ?>
      </table>
    </section>
    <form method="post" action="admin_controller.php">
      <input type='hidden' name='action' id='action' value=''>
      <label for="table">Select Table to Export:</label>
      <select name="table" id="table">
          <option value="admins">Admins</option>
          <option value="Category">Category</option>
          <option value="Incredient">Incredient</option>
          <option value="NutritionFact">NutritionFact</option>
          <option value="Preferences">Preferences</option>
          <option value="Product">Product</option>
          <option value="ProductCategories">ProductCategories</option>
          <option value="ProductIncredients">ProductIncredients</option>
          <option value="ProductNutritionFacts">ProductNutritionFacts</option>
          <option value="ProductStores">ProductStores</option>
          <option value="Store">Store</option>
          <option value="users">Users</option>
          <option value="user_preferences">User Preferences</option>
          <option value="user_shopping_list">User Shopping List</option>
      </select>
      <button type="submit" name="export_csv" onclick="document.getElementById('action').value='exportToCSV'">Export to CSV</button>
      <button type="submit" name="export_pdf" onclick="document.getElementById('action').value='exportToPDF'">Export to PDF</button>
    </form>
  </body>
</html>