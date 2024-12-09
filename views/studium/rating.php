<?php if ($is_logged_in): ?>
  <?php if ($has_rated): ?>
    <h2>You Already Rated!</h2>
    <p>Rating: <?php echo $user_rating; ?> / 5</p>
  <?php else: ?>
    <form method="POST">
      <label for="rating">Rate this Studium (1 to 5):</label>
      <input type="number" id="rating" name="rating" min="1" max="5" required />
      <button type="submit">Submit</button>
    </form>
  <?php endif; ?>
<?php else: ?>
  <h2>You have to sign in to rate!</h2>
<?php endif; ?>


<?php if (empty($ratings)): ?> <p>No ratings yet.</p>
<?php endif; ?>