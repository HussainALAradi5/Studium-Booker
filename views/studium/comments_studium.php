<?php
// comments_studium.php - Displays comments and handles comment submission

// Fetch the comments for the current studium
$studium_id = $_GET['id']; // Get the studium ID from the query parameter

function get_comments($studium_id)
{
  global $pdo;
  $sql = "SELECT c.comment, c.commented_by_user, u.user_name, c.commented_at
            FROM comment c
            JOIN user u ON c.commented_by_user = u.user_id
            WHERE c.studium_id = ?
            ORDER BY c.commented_at DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$comments = get_comments($studium_id);
?>

<h2>Comments</h2>
<?php foreach ($comments as $comment): ?>
  <div class="comment">
    <p><strong><?php echo htmlspecialchars($comment['user_name']); ?></strong> says:</p>
    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
    <p><small>Posted on <?php echo date('F j, Y, g:i a', strtotime($comment['commented_at'])); ?></small></p>
  </div>
<?php endforeach; ?>

<!-- Comment submission form -->
<form method="POST" action="submit_comment.php">
  <input type="hidden" name="studium_id" value="<?php echo $studium_id; ?>">
  <textarea name="comment" placeholder="Leave a comment" required></textarea>
  <input type="submit" value="Submit Comment">
</form>