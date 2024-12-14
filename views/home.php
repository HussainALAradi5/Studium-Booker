<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

</head>

<body>

  <div class="container mt-4">
    <?php
    if (isset($_COOKIE['user_id'])) {
      $user = get_user_details($_COOKIE['user_id']);
      if ($user) {
        echo "<h1 class='text-center'>Hi, " . htmlspecialchars($user['user_name']) . "! Welcome to the Home Page</h1>";
      } else {
        echo "<h1 class='text-center'>Welcome to the Home Page</h1>";
        echo "<p class='text-center'>Please <a href='index.php?action=login'>Login</a> or <a href='index.php?action=register'>Register</a></p>";
      }
    } else {
      echo "<h1 class='text-center'>Welcome to the Home Page</h1>";
    }
    ?>

    <div class="row mt-4">
      <div class="col-md-12">
        <div class="studium-cards d-flex flex-wrap justify-content-center">
          <?php include './views/studium/studiums.php'; ?>
        </div>
      </div>
    </div>
  </div>


</body>

</html>