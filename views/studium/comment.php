<h2>Comments</h2>
<?php if (!$is_logged_in): ?>
  <div class="alert alert-warning">
    <h2 id="log_par">Log in to comment.</h2>
  </div>
<?php elseif ($is_logged_in && $user_comment): ?>
  <div class="alert alert-info">
    <h2>You Already Commented!</h2>
  </div>
<?php endif; ?>
<div class="comments mt-3">
  <?php if (!empty($comments)): ?>
    <?php foreach ($comments as $comment): ?>
      <div class="comment mb-3 p-3 border rounded">
        <strong><?php echo htmlspecialchars($comment['user_name']); ?>:</strong>
        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
        <small class="text-muted"><?php echo date('F j, Y', strtotime($comment['comment_at'])); ?></small>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">No comments yet.</p>
  <?php endif; ?>
</div>

<?php if ($is_logged_in && !$user_comment): ?>
  <h3>Leave a Comment</h3>
  <form method="POST">
    <textarea name="comment" class="form-control mb-2" rows="3" required placeholder="Write your comment"></textarea>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
<?php endif; ?>