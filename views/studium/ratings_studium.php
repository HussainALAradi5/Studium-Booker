<?php
require_once 'auth/security.php'; // Include the auth functions

function get_ratings($studium_id)
{
  return get_all_ratings($studium_id); // Fetch ratings for a studium
}

function render_rating_stars($is_logged_in)
{
  if ($is_logged_in) {
    echo '<div id="rating-stars">';
    for ($i = 0; $i < 5; $i++) {
      echo '<span class="star" data-value="' . ($i + 1) . '">&#9733;</span>';
    }
    echo '</div>';
  } else {
    echo '<p>You must be logged in to rate this studium.</p>';
  }
}

function render_ratings($ratings)
{
  if ($ratings) {
    echo '<ul>';
    foreach ($ratings as $rating) {
      echo '<li>
                    User ID: ' . $rating['rated_by_user'] . ' |
                    Rating: ' . $rating['rate'] . ' |
                    Rated At: ' . date('F j, Y, g:i a', strtotime($rating['rated_at'])) . '
                  </li>';
    }
    echo '</ul>';
  } else {
    echo '<p>No ratings yet for this studium.</p>';
  }
}
