<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/user/stylesheets/reset-password.css">
    <link rel="stylesheet" type="text/css" href="../views/user/responsive/reset-password.css">
    <title>Reset password</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer</h1>
    </div>
    
    <div class="login-container">
        <h2>Reset password</h2>
        <form action="culinary_controller.php" method="post">
            <input type="hidden" name="action" value="resetPass">
            <input type="password" name="oldPassword" placeholder="Enter old password" required>
            <input type="password" name="newPassword" placeholder="Enter new password" required>
            <input type="password" name="newPasswordRepeat" placeholder="Retype new password" required>
            <p style="color:red"><?php if(isset($errorMessage4)) { echo $errorMessage4; } ?></p>
            <input type="submit" value="Reset password">
        </form>
    </div>
  </body>
</html>