<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <link rel="stylesheet" href="css/nav_bar.css">
</head>

<body>
  <header>
    <nav>
      <ul class="navbar">
        <li><a href="index.php?action=home">Home</a></li>

        <?php if (!isset($_COOKIE['user_id'])): ?>
          <!-- Show Register and Login if user is not logged in -->
          <li><a href="index.php?action=register">Register</a></li>
          <li><a href="index.php?action=login">Login</a></li>
        <?php else: ?>
          <!-- Show Profile and Logout if user is logged in -->
          <li><a href="index.php?action=profile">Profile</a></li>
          <li><a href="index.php?action=logout">Logout</a></li>
          <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == 1): ?>
            <!-- Show Admin Panel link if the user is an admin -->
            <li><a href="index.php?action=admin_panel">Admin Panel</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
</body>

</html>