<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/user/stylesheets/signup.css">
    <link rel="stylesheet" type="text/css" href="../views/user/responsive/signup.css">
    <title>Sign up</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer</h1>
    </div>
    
    <div class="login-container">
        <div class="back-button-container">
          <a class="back-button" href="javascript:history.back()">< Back</a>
        </div>
        <h2>Sign up</h2>
        <form action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="addCred">
            <input type="text" name="user_name" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password2" placeholder="Retype Password" required>
            <p style="color:red"><?php if(isset($errorMessage2)) { echo $errorMessage2; } ?></p>
            <p>Already have an account? <a href="culinary_controller.php?action=login">Log in</a></p>
            <input type="submit" value="Sign up">
        </form>
    </div>
   
  </body>
</html>