<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

  <link rel="stylesheet" href="../css/login_register.css">

</head>

<body>
  <?php
  if (isset($_COOKIE['user_id'])) {
    echo "<h1>Welcome to the Home Page</h1>";
    echo "<p>You're logged in.</p>";
    echo "<a href='index.php?action=profile'>Go to your profile</a>";
    echo "<a href='index.php?action=logout'>Logout</a>";
  } else {
    echo "<h1>Welcome to the Home Page</h1>";
    echo "<p>Please <a href='index.php?action=login'>Login</a> or <a href='index.php?action=register'>Register</a></p>";
  }
  ?>
</body>

</html>