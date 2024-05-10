<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/stylesheets/reset-password.css">
    <link rel="stylesheet" type="text/css" href="../views/responsive/reset-password.css">
    <title>Reset password</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer</h1>
    </div>
    
    <div class="login-container">
        <h2>Reset password</h2>
        <form action="culinary_controller.php?action=changepass" method="post">
            <input type="text" name="username" placeholder="Enter username or email" required>
            <input type="text" name="oldpassword" placeholder="Enter old password" required>
            <input type="text" name="newpassword" placeholder="Enter new password" required>
            <p style="color:red"><?php if(isset($errorMessage4)) { echo $errorMessage4; } ?></p>
            <p style="color:green"><?php if(isset($passreset)) { echo $passreset; } ?></p>
            <input type="submit" value="Reset password">
        </form>
    </div>
    <p>Photo by <a href="https://unsplash.com/@amseaman?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Andrew Seaman</a> on <a href="https://unsplash.com/photos/a-blurry-photo-of-a-bunch-of-lights-J65VbXRtgrU?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a></p>
  </body>
</html>