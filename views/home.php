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
    require_once './model/studium.php'; // Include model to fetch studiums and ratings

    $studiums = view_studiums(); // Get all studiums

    if (count($studiums) == 0) {
      // If there are no studiums, display a message
      echo "<h1 class='no-studium-message'>Sorry, there are no studiums!</h1>";
    } else {
      // Display studium cards
      foreach ($studiums as $studium) {
        $average_rating = get_average_rating($studium['studium_id']); // Get average rating
        echo "<div class='studium-card'>";
        echo "<h3>" . htmlspecialchars($studium['studium_name']) . "</h3>";
        echo "<p>Location: " . htmlspecialchars($studium['location']) . "</p>";
        echo "<p>Price per hour: $" . htmlspecialchars($studium['price_per_hour']) . "</p>";
        echo "<p>Average Rating: " . $average_rating . " / 5</p>";
        echo "<a href='studim.php?id=" . $studium['studium_id'] . "'>View Details</a>"; // Link to individual studium page
        echo "</div>";
      }
    }
    ?>
  </div>

</body>

</html>