<?php
require_once './auth/security.php'; // Include security functions
require_once './model/studium.php'; // Studium model
require_once './model/rating.php'; // Rating model
require_once './model/comment.php'; // Comment model

// Fetch and validate studium ID from the URL
$studium_id = $_GET['id'] ?? null;
if (!$studium_id || !($studium = view_studium($studium_id))) {
  die("Invalid studium ID.");
}

// Fetch related data
$average_rating = get_average_rating($studium_id);
$comments = get_comments_by_studium($studium_id);
$ratings = get_all_ratings($studium_id);

// Check user authentication
$is_logged_in = false;
$user_id = null;
$user_comment = null;

try {
  $user_id = validate_user_logged_in();
  $is_logged_in = true;
  $user_comment = user_has_commented($studium_id, $user_id);
} catch (Exception $e) {
  // User not logged in
}

// Handle POST comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && $is_logged_in) {
  $comment = secure_input($_POST['comment']);
  $response = add_comment($studium_id, $comment);
  echo "<script>alert('" . addslashes($response) . "');</script>";
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit;
}

// Handle POST rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && $is_logged_in) {
  $rating = intval($_POST['rating']);
  try {
    add_rating($studium_id, $rating);
    echo "<script>alert('Rating added successfully.');</script>";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  } catch (Exception $e) {
    echo "<script>alert('" . addslashes($e->getMessage()) . "');</script>";
  }
}

// Check if the user has already rated the studium
$user_rating = get_user_rating($studium_id, $user_id);
$has_rated = $user_rating !== false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($studium['studium_name']); ?> - Details</title>
  <link rel="stylesheet" href="css/studium.css?v=<?php echo time(); ?>">
  <script src="scripts/rating.js" defer></script>
  <script>
    const studiumId = <?php echo $studium_id; ?>;
    const userId = <?php echo $user_id; ?>; // Ensure you pass user_id if you need to use it in the rating submission
  </script>
</head>

<body>
  <div class="studium-details">
    <h1><?php echo htmlspecialchars($studium['studium_name']); ?></h1>
    <p>Location: <?php echo htmlspecialchars($studium['location']); ?></p>
    <p>Price per hour: <?php echo htmlspecialchars($studium['price_per_hour']); ?> BD</p>
    <p>Average Rating: <?php echo $average_rating; ?> / 5</p>
  </div>

  <?php include 'rating.php'; ?>
  <?php include 'comment.php'; ?>
</body>

</html>