<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/stylesheets/login.css">
    <link rel="stylesheet" type="text/css" href="../views/responsive/login.css">
    <title>Login</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer</h1>
    </div>

    
    <div class="login-container">
        <div class="back-button-container">
          <a class="back-button" href="javascript:history.back()">< Back</a>
        </div>
        <h2>Login</h2>
        <form action="culinary_controller.php?action=checkCred" method="post">
            <input type="text" name="user_name" placeholder="Username/email" required>
            <input type="password" name="password" placeholder="Password" required>
            <p style="color:red"><?php if(isset($errorMessage)) { echo $errorMessage; } ?></p>
            <p> Don't have an account? <a href="culinary_controller.php?action=signup">Sign up now!</a> </p>
            <p><input type="checkbox" id="rememberme" name="rememberme" value="no">
            <label for="rememberme"> Remember me?</label></p>
            <input type="submit" value="Login">
        </form>
    </div>
  </body>
</html>