<?php
require_once './auth/security.php';

// Add a new comment
// Add a new comment
function add_comment($studium_id, $comment)
{
  global $pdo;

  // Validate logged-in user and get user ID
  try {
    $user_id = validate_user_logged_in();
  } catch (Exception $e) {
    return "You must be logged in to comment.";
  }

  // Check if the user has already commented on this studium
  if (user_has_commented($studium_id, $user_id)) {
    return "You have already commented on this studium.";
  }

  // Sanitize and insert the comment into the database
  try {
    $sql = "INSERT INTO comment (comment, studium_id, comment_by) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([secure_input($comment), $studium_id, $user_id]);

    return "Comment added successfully.";
  } catch (Exception $e) {
    return "Error inserting comment: " . $e->getMessage();
  }
}


// Check if the user has already commented on the studium
function user_has_commented($studium_id, $user_id)
{
  global $pdo;

  $sql = "SELECT COUNT(*) FROM comment WHERE studium_id = ? AND comment_by = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id, $user_id]);

  // If the user has commented, return true (count > 0)
  return $stmt->fetchColumn() > 0;
}


// Get all comments for a studium
function get_comments_by_studium($studium_id)
{
  global $pdo;

  $sql = "SELECT c.comment, c.comment_by, u.user_name, c.comment_at
            FROM comment c 
            JOIN user u ON c.comment_by = u.user_id 
            WHERE c.studium_id = ? 
            ORDER BY c.comment_at DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
