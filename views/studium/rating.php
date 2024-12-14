<?php if ($is_logged_in): ?>
  <?php if ($has_rated): ?>
    <div class="alert alert-info">
      <h2>You Already Rated!</h2>
    </div>
  <?php else: ?>
    <form method="POST" class="form-inline">
      <label for="rating" class="mr-2">Rate this Studium (1 to 5):</label>
      <input type="number" id="rating" name="rating" min="1" max="5" class="form-control mr-2" required />
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  <?php endif; ?>
<?php else: ?>
  <div class="alert alert-warning">
    <h2>You have to sign in to rate!</h2>
  </div>
<?php endif; ?>

<?php if (empty($ratings)): ?>
  <p class="text-muted">No ratings yet.</p>
<?php endif; ?>