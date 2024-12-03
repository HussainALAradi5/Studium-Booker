<?php


// Get studium details
$studium_id = $_GET['id'] ?? 0; // Get studium ID from the URL
$studium = view_studium($studium_id); // Get studium details

// Get average rating for the studium
$average_rating = get_average_rating($studium_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($studium['studium_name']); ?> - Details</title>
  <link rel="stylesheet" href="css/studium.css">
</head>

<body>

  <div class="studium-details">
    <h1><?php echo htmlspecialchars($studium['studium_name']); ?></h1>
    <p>Location: <?php echo htmlspecialchars($studium['location']); ?></p>
    <p>Price per hour:
      <?php echo htmlspecialchars($studium['price_per_hour']) . " BD";
      ?></p>
    <p>Average Rating: <?php echo $average_rating; ?> / 5</p>
  </div>

  <?php include 'ratings_studium.php'; ?>

  <?php include 'comments_studium.php'; ?>

</body>

</html>