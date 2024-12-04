<?php
// ratings_studium.php - Displays all ratings and handles rating submission
include_once './model/rating.php'; // Include the rating model

// Fetch the studium ID from the query parameter
$studium_id = $_GET['id'];

// Fetch all ratings for the studium
$ratings = get_all_ratings($studium_id);

// Check if the user is logged in (used to conditionally display the rating form)
$user_id = null;
$is_logged_in = false;

try {
  $user_id = validate_user_logged_in();
  $is_logged_in = true;
} catch (Exception $e) {
  // User is not logged in, continue displaying ratings but not the form
}

?>

<h2>Ratings</h2>

<!-- Display all ratings for the studium -->
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

<!-- Display rating submission form only if the user is logged in -->
<?php if ($is_logged_in): ?>
  <h3>Your Rating</h3>
  <div id="rating-stars">
    <?php for ($i = 0; $i < 5; $i++): ?>
      <span class="star" data-value="<?php echo $i + 1; ?>">&#9733;</span>
    <?php endfor; ?>
  </div>

  <!-- Rating form -->
  <form method="POST" action="submit_rating.php">
    <input type="hidden" name="studium_id" value="<?php echo $studium_id; ?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="rating" id="rating-value">
    <input type="submit" value="Submit Rating">
  </form>
<?php else: ?>
  <p>You must be logged in to rate this studium.</p>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    let selectedRating = 0;

    // Hover effect
    stars.forEach(star => {
      star.addEventListener('mouseover', function() {
        const rating = parseFloat(star.dataset.value);
        updateStars(rating);
      });

      star.addEventListener('mouseout', function() {
        updateStars(selectedRating);
      });

      star.addEventListener('click', function() {
        selectedRating = parseFloat(star.dataset.value);
        document.getElementById('rating-value').value = selectedRating;
        updateStars(selectedRating, true); // Mark the stars as fixed (golden)
      });
    });

    function updateStars(rating, fixed = false) {
      stars.forEach(star => {
        const starValue = parseFloat(star.dataset.value);
        if (starValue <= rating) {
          star.classList.add(fixed ? 'fixed' : 'hover');
        } else {
          star.classList.remove('hover', 'fixed');
        }
      });
    }
  });
</script>