<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="css/login_register.css">
  <link rel="stylesheet" href="css/studims.css"> <!-- Include the CSS for studium cards -->
</head>

<body>
  <?php
  // Include the user model to access the get_user_details function
  require_once './model/user.php';

  // Check if user is logged in
  if (isset($_COOKIE['user_id'])) {
    // Get user details using the user_id from the cookie
    $user = get_user_details($_COOKIE['user_id']);

    // Check if user data is returned
    if ($user) {
      $user_name = htmlspecialchars($user['user_name']);
      echo "<h1>Hi, $user_name! Welcome to the Home Page</h1>";
    } else {
      echo "<h1>Welcome to the Home Page</h1>";
      echo "<p>Please <a href='index.php?action=login'>Login</a> or <a href='index.php?action=register'>Register</a></p>";
    }
  } else {
    // If the user is not logged in
    echo "<h1>Welcome to the Home Page</h1>";
  }
  ?>

  <div class="studium-cards">
    <?php
    // Include studiums.php to display studium cards
    include './views/studium/studiums.php';
    ?>
  </div>

</body>

</html>