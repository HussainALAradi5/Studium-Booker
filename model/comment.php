<?php
require_once './auth/security.php';

// Add a new comment
function add_comment($studium_id, $comment)
{
  global $pdo;

  // Validate logged-in user and get user ID
  $user_id = validate_user_logged_in();

  // Sanitize and insert the comment into the database
  $sql = "INSERT INTO comment (comment, studium_id, commented_by_user) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([secure_input($comment), $studium_id, $user_id]);

  return "Comment added successfully.";
}

// Get all comments for a studium
function get_comments_by_studium($studium_id)
{
  global $pdo;

  $sql = "SELECT c.comment, c.commented_by_user, u.user_name, c.commented_at 
            FROM comment c 
            JOIN user u ON c.commented_by_user = u.user_id 
            WHERE c.studium_id = ? 
            ORDER BY c.commented_at DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
