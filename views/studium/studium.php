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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($studium['studium_name']); ?> - Details</title>
  <link rel="stylesheet" href="css/studium.css?v=<?php echo time(); ?>">
  <script src="scripts/rating.js" defer></script>
</head>

<body>
  <div class="studium-details">
    <h1><?php echo htmlspecialchars($studium['studium_name']); ?></h1>
    <p>Location: <?php echo htmlspecialchars($studium['location']); ?></p>
    <p>Price per hour: <?php echo htmlspecialchars($studium['price_per_hour']); ?> BD</p>
    <p>Average Rating: <?php echo $average_rating; ?> / 5</p>
  </div>

  <h2>Ratings</h2>
  <?php if ($is_logged_in): ?>
    <div id="rating-stars">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <span class="star" data-value="<?php echo $i; ?>">&#9733;</span>
      <?php endfor; ?>
    </div>
  <?php else: ?>
    <p>Log in to rate this studium.</p>
  <?php endif; ?>

  <?php if (!empty($ratings)): ?>
    <ul>
      <?php foreach ($ratings as $rating): ?>
        <li>
          Rated by User ID: <?php echo $rating['rated_by_user']; ?> |
          Rating: <?php echo $rating['rate']; ?> |
          Date: <?php echo date('F j, Y', strtotime($rating['rated_at'])); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No ratings yet.</p>
  <?php endif; ?>

  <h2>Comments</h2>
  <?php if (!$is_logged_in): ?>
    <h2 id="log_par">Log in to comment.</h2>
  <?php elseif ($is_logged_in && $user_comment): ?>
    <h2>You Already Comment!</h2>
  <?php endif; ?>
  <div class="comments">
    <?php if (!empty($comments)): ?>
      <?php foreach ($comments as $comment): ?>
        <div class="comment">
          <strong><?php echo htmlspecialchars($comment['user_name']); ?>:</strong>
          <p><?php echo htmlspecialchars($comment['comment']); ?></p>
          <small><?php echo date('F j, Y', strtotime($comment['comment_at'])); ?></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No comments yet.</p>
    <?php endif; ?>
  </div>

  <?php if ($is_logged_in && !$user_comment): ?>
    <h3>Leave a Comment</h3>

    <form method="POST">
      <textarea name="comment" required placeholder="Write your comment"></textarea>
      <button type="submit">Submit</button>
    </form>



  <?php endif; ?>
</body>

</html>