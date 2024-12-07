<?php
require_once 'auth/security.php'; // Include the auth functions

function get_comments($studium_id)
{
  return get_comments_by_studium($studium_id); // Fetch comments for a studium
}

function handle_comment_submission($studium_id)
{
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    // Sanitize and add the comment to the database
    $comment = $_POST['comment'];
    $response = add_comment($studium_id, $comment);
    echo "<script>alert('" . $response . "');</script>";
    // Reload the page to show the new comment
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  }
}

function render_comment_form($studium_id, $user_id)
{
  $user_comment = $user_id ? user_has_commented($studium_id, $user_id) : null;
  if ($user_id) {
    if (!$user_comment) {
      echo '<form method="POST" id="comment-form">
                    <textarea name="comment" placeholder="Leave a comment" required></textarea>
                    <button type="submit">Submit Comment</button>
                  </form>';
    } else {
      echo '<p>You have already left a comment for this studium.</p>';
    }
  } else {
    echo '<p>You must be logged in to leave a comment.</p>';
  }
}

function render_comments($comments)
{
  if ($comments) {
    foreach ($comments as $comment) {
      echo '<div class="comment">
                    <p><strong>' . htmlspecialchars($comment['user_name']) . '</strong> says:</p>
                    <p>' . htmlspecialchars($comment['comment']) . '</p>
                    <p><small>Posted on ' . date('F j, Y, g:i a', strtotime($comment['comment_at'])) . '</small></p>
                  </div>';
    }
  } else {
    echo '<p>No comments yet for this studium.</p>';
  }
}
