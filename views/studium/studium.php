<?php
require_once 'auth/security.php'; // Include the auth functions

// Get studium details
$studium_id = $_GET['id'] ?? 0; // Get studium ID from the URL
$studium = view_studium($studium_id); // Get studium details

// Get average rating for the studium
$average_rating = get_average_rating($studium_id);

// Fetch the comments for the current studium
$comments = get_comments_by_studium($studium_id);

// Fetch all ratings for the studium
$ratings = get_all_ratings($studium_id);

// Initialize variables for user login status
$is_logged_in = false;
$user_id = null;
$user_comment = null;

try {
  // Check if the user is logged in
  $user_id = validate_user_logged_in(); // This will set user_id if logged in
  $is_logged_in = true;

  // Check if the logged-in user has already commented on this studium
  $user_comment = user_has_commented($studium_id, $user_id); // This checks if the user has already commented
} catch (Exception $e) {
  // User is not logged in, $is_logged_in remains false
  $user_id = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($studium['studium_name']); ?> - Details</title>
  <link rel="stylesheet" href="css/studium.css">
  <script src="./scripts/rating.js" defer></script> <!-- Rating Script -->
</head>

<body>

  <div class="studium-details">
    <h1><?php echo htmlspecialchars($studium['studium_name']); ?></h1>
    <p>Location: <?php echo htmlspecialchars($studium['location']); ?></p>
    <p>Price per hour: <?php echo htmlspecialchars($studium['price_per_hour']) . " BD"; ?></p>
    <p>Average Rating: <?php echo $average_rating; ?> / 5</p>
  </div>

  <h2>Ratings</h2>
  <!-- Display general ratings from users -->
  <?php if ($is_logged_in): ?>
    <div id="rating-stars">
      <?php for ($i = 0; $i < 5; $i++): ?>
        <span class="star" data-value="<?php echo $i + 1; ?>">&#9733;</span>
      <?php endfor; ?>
    </div>
  <?php else: ?>
    <p>You must be logged in to rate this studium.</p>
  <?php endif; ?>

  <p>Average Rating: <?php echo $average_rating; ?> / 5</p>

  <?php if ($ratings): ?>
    <ul>
      <?php foreach ($ratings as $rating): ?>
        <li>
          User ID: <?php echo $rating['rated_by_user']; ?> |
          Rating: <?php echo $rating['rate']; ?> |
          Rated At: <?php echo date('F j, Y, g:i a', strtotime($rating['rated_at'])); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No ratings yet for this studium.</p>
  <?php endif; ?>

  <h2>Comments</h2>
  <!-- Display comments -->
  <?php foreach ($comments as $comment): ?>
    <div class="comment">
      <p><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong> says:</p>
      <p><?php echo htmlspecialchars($comment['comment']); ?></p>
      <p><small>Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['comment_at'])); ?></small></p>
    </div>
  <?php endforeach; ?>

  <?php if ($is_logged_in): ?>
    <h3>Leave a Comment</h3>
    <?php if (!$user_comment): ?>
      <form method="POST" action="submit_comment.php">
        <input type="hidden" name="studium_id" value="<?php echo $studium_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <textarea name="comment" placeholder="Leave a comment" required></textarea>
        <input type="submit" value="Submit Comment">
      </form>
    <?php else: ?>
      <p>You have already left a comment for this studium.</p>
    <?php endif; ?>
  <?php else: ?>
    <p>You must be logged in to leave a comment.</p>
  <?php endif; ?>

  <script>
    const studiumId = <?php echo json_encode($studium_id); ?>;
    const userId = <?php echo json_encode($user_id); ?>;
  </script>

</body>

</html>