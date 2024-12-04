<?php
require_once './auth/security.php';

// Add a new rating
function add_rating($studium_id, $rate)
{
  global $pdo;

  // Validate logged-in user and get user ID
  $user_id = validate_user_logged_in();

  if ($rate < 0 || $rate > 5) {
    throw new Exception("Rating must be between 0 and 5.");
  }

  // Insert the rating into the database
  $sql = "INSERT INTO rating (rate, studium_id, rated_by_user) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$rate, $studium_id, $user_id]);

  return "Rating added successfully.";
}

// Get total ratings for a studium
function get_total_ratings($studium_id)
{
  global $pdo;

  $sql = "SELECT COUNT(*) FROM rating WHERE studium_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return $stmt->fetchColumn();
}

// Get average rating for a studium
function get_average_rating($studium_id)
{
  global $pdo;

  $sql = "SELECT AVG(rate) FROM rating WHERE studium_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return round($stmt->fetchColumn(), 2); // Round to 2 decimal places
}

// Fetch the user's rating for a given studium
function get_user_rating($studium_id, $user_id)
{
  global $pdo;
  $sql = "SELECT rate FROM rating WHERE studium_id = ? AND rated_by_user = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id, $user_id]);
  return $stmt->fetchColumn();
}
