<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <!-- Include Bootstrap CSS for styling -->
</head>

<body>

  <!-- Header -->

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
        <div class="card-deck">
          <?php
          $studiums = view_studiums(); // Get all studiums
          foreach ($studiums as $studium) {
            $average_rating = get_average_rating($studium['studium_id']); // Get average rating
            echo "<div class='card mb-4'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($studium['studium_name']) . "</h5>";
            echo "<p class='card-text'>Location: " . htmlspecialchars($studium['location']) . "</p>";
            echo "<p class='card-text'>Price per hour: " . htmlspecialchars($studium['price_per_hour']) . " BD</p>";
            echo "<p class='card-text'>Average Rating: " . $average_rating . " / 5</p>";
            echo "<a href='index.php?action=studium&id=" . $studium['studium_id'] . "' class='btn btn-primary'>View Details</a>";
            echo "</div>";
            echo "</div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>


  ?>

</body>

</html>