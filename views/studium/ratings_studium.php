<?php
include_once './model/rating.php';
// Fetch current user's rating if it exists
$user_id = validate_user_logged_in();
$studium_id = $_GET['id']; // Get the studium ID from the query parameter

// Fetch the existing rating for the user, if available
$current_rating = get_user_rating($studium_id, $user_id);
?>

<div id="rating-stars">
  <?php for ($i = 0; $i < 5; $i++): ?>
    <span class="star" data-value="<?php echo $i + 1; ?>">
      &#9733; <!-- Star symbol -->
    </span>
  <?php endfor; ?>
</div>

<form method="POST" action="submit_rating.php">
  <input type="hidden" name="studium_id" value="<?php echo $studium_id; ?>">
  <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" name="rating" id="rating-value" value="<?php echo $current_rating; ?>">
  <input type="submit" value="Submit Rating" <?php echo $current_rating ? 'disabled' : ''; ?>> <!-- Disable button if user has rated already -->
</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    let selectedRating = <?php echo $current_rating ? $current_rating : 0; ?>;

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
        document.getElementById('rating-value').value = selectedRating; // Update hidden field with selected rating
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