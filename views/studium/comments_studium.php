<?php
// comments_studium.php - Displays comments and handles comment submission
include_once './model/comment.php'; // Use include_once or require_once
include_once './auth/security.php'; // To check user login status

// Fetch the comments for the current studium
$studium_id = $_GET['id']; // Get the studium ID from the query parameter
$comments = get_comments_by_studium($studium_id);

// Check if the user is logged in (used to conditionally display the comment form)
$user_id = null;
$is_logged_in = false;
$user_comment = null;

try {
  $user_id = validate_user_logged_in(); // Check if user is logged in
  $is_logged_in = true;

  // Check if the logged-in user has already commented on this studium
  $user_comment = user_has_commented($studium_id, $user_id);
} catch (Exception $e) {
}

?>

<h2>Comments</h2>

<!-- Display all comments for the studium -->
<?php foreach ($comments as $comment): ?>
  <div class="comment">
    <p><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong> says:</p>
    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
    <p><small>Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['comment_at'])); ?></small></p>
  </div>
<?php endforeach; ?>

<!-- Display comment submission form only if the user is logged in and has not commented -->
<?php if ($is_logged_in): ?>
  <h3>Leave a Comment</h3>
  <?php if (!$user_comment): // Show the form only if the user hasn't commented 
  ?>
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