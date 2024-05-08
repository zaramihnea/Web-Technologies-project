<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="../views/stylesheets/signup.css">
    <link rel="stylesheet" type="text/css" href="../views/responsive/signup.css">
    <title>Sign up</title>
  </head>
  <body>
    <div class="title-container">
        <h1>Culinary Preferences Organizer</h1>
    </div>
    
    <div class="login-container">
        <h2>Sign up</h2>
        <form action="culinary_controller.php?action=addCred" method="post">
            <input type="text" name="user_name" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password2" placeholder="Retype Password" required>
            <p style="color:red"><?php if(isset($errorMessage2)) { echo $errorMessage2; } ?></p>
            <p>Already have an account? <a href="culinary_controller.php?action=login">Log in</a></p>
            <input type="submit" value="Sign up">
        </form>
    </div>
    <p>Photo by <a href="https://unsplash.com/@amseaman?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Andrew Seaman</a> on <a href="https://unsplash.com/photos/a-blurry-photo-of-a-bunch-of-lights-J65VbXRtgrU?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a></p>
  </body>
</html>