<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
      <?php
      $studiums = view_studiums();
      foreach ($studiums as $index => $studium) :
        $average_rating = get_average_rating($studium['studium_id']);
      ?>
        <div class="col-md-3 mb-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($studium['studium_name']); ?></h5>
              <p class="card-text">Location: <?php echo htmlspecialchars($studium['location']); ?></p>
              <p class="card-text">Price per hour: <?php echo htmlspecialchars($studium['price_per_hour']); ?> BD</p>
              <p class="card-text">Average Rating: <?php echo $average_rating; ?> / 5</p>
              <a href="index.php?action=studium&id=<?php echo $studium['studium_id']; ?>" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</body>

</html>