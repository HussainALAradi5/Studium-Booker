<h2>Comments</h2>
<?php if (!$is_logged_in): ?>
  <h2 id="log_par">Log in to comment.</h2>
<?php elseif ($is_logged_in && $user_comment): ?>
  <h2>You Already Commented!</h2>
<?php endif; ?>
<div class="comments">
  <?php if (!empty($comments)): ?>
    <?php foreach ($comments as $comment): ?>
      <div class="comment">
        <strong><?php echo htmlspecialchars($comment['user_name']); ?>:</strong>
        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
        <?php if ($is_logged_in && $has_rated): ?>
        <?php endif; ?>
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