<?php
// comments_studium.php - Displays comments and handles comment submission
include_once './model/comment.php'; // Use include_once or require_once

// Fetch the comments for the current studium
$studium_id = $_GET['id']; // Get the studium ID from the query parameter
$comments = get_comments_by_studium($studium_id);
?>

<h2>Comments</h2>
<?php foreach ($comments as $comment): ?>
  <div class="comment">
    <p><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong> says:</p>
    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
    <p><small>Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['comment_at'])); ?></small></p>
  </div>
<?php endforeach; ?>

<!-- Comment submission form -->
<form method="POST" action="submit_comment.php">
  <input type="hidden" name="studium_id" value="<?php echo $studium_id; ?>">
  <textarea name="comment" placeholder="Leave a comment" required></textarea>
  <input type="submit" value="Submit Comment">
</form>