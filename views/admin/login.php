<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/admin/stylesheets/login.css">
    <link rel="stylesheet" type="text/css" href="../views/admin/responsive/login.css">
    <title>Login</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer - Admin dashboard</h1>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="admin_controller.php" method="post">
            <input type="hidden" name="action" value="checkCred">
            <input type="text" name="user_name" placeholder="Username/email" required>
            <input type="password" name="password" placeholder="Password" required>
            <p style="color:red"><?php if(isset($errorMessage)) { echo $errorMessage; } ?></p>
            <p><input type="checkbox" id="rememberme" name="rememberme" value="no">
            <label for="rememberme"> Remember me?</label></p>
            <input type="submit" value="Login">
        </form>
    </div>
  </body>
</html>