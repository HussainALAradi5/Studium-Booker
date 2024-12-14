<?php
require_once './model/user.php';
require_once './auth/security.php';
require_once './model/reservation.php';
require_once './model/studium.php'; // Include model to fetch studium details
require_once './model/rating.php'; // Include model to fetch ratings
require_once './model/comment.php'; // Include model to fetch comments
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/nav_bar.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="index.php?action=home">Your Website</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.php?action=home">Home</a></li>
          <?php if (!isset($_COOKIE['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="index.php?action=register">Register</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?action=login">Login</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="index.php?action=profile">Profile</a></li>
            <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == 1): ?>
              <li class="nav-item"><a class="nav-link" href="index.php?action=admin_panel">Admin Panel</a></li>
              <li class="nav-item"><a class="nav-link" href="index.php?action=add_studium">Add Studium</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="index.php?action=logout">Logout</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Your other content goes here -->

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>