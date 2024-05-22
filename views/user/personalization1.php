<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <meta name="description" content="">
  <link rel="stylesheet" href="../views/user/stylesheets/personalization.css">
  <link rel="stylesheet" href="../views/user/responsive/personalization.css">
  <title>Personalize your account</title>
</head>
<body>

  <div class="title-container">
    <h1>Welcome! Personalize you profile</h1>
  </div>

  <div class="preference-container">
    <p>Let us know you:</p>
    <form action="culinary_controller.php" method="post">
        <input type="hidden" name="action" value="pref1">
        <input type="text" name="email" placeholder="Email" required>
        <input type="text" name="firstName" placeholder="First name" required>
        <input type="text" name="lastName" placeholder="Last name" required>
        <input type="number" name="age" placeholder="Age" required>
        <select id="gender" name="gender" required>
          <option value="" disabled selected>Select gender</option>
          <option value="0">Female</option>
          <option value="1">Male</option>
          <option value="2">Other</option>
        </select>
        <p style="color:red"><?php if(isset($errorMessage3)) { echo $errorMessage3; } ?></p>
        <input type="submit" value="Next">
    </form>
</div>
</body>
</html>
