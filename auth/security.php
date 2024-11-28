<?php
require_once './db/database.php'; // Ensure database connection is established

// Secure input function to sanitize user input
function secure_input($input)
{
  return htmlspecialchars(trim($input));
}

// Check if the user already exists (by email or username)
function user_exists($email, $user_name)
{
  global $pdo;

  // SQL query to check if the user exists
  $sql = "SELECT COUNT(*) FROM user WHERE LOWER(email) = ? OR LOWER(user_name) = ?";

  // Prepare and execute the query
  $stmt = $pdo->prepare($sql);
  $stmt->execute([strtolower($email), strtolower($user_name)]);
  return $stmt->fetchColumn() > 0;
}

// Fetch user by email or username (active users only)
function fetch_user_by_identifier($identifier)
{
  global $pdo;

  // SQL query to fetch user by identifier
  $sql = "SELECT * FROM user WHERE (LOWER(email) = ? OR LOWER(user_name) = ?) AND is_active = 1";

  // Prepare and execute the query
  $stmt = $pdo->prepare($sql);
  $stmt->execute([strtolower($identifier), strtolower($identifier)]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verify user password during login
function verify_password($password, $password_digest)
{
  return password_verify($password, $password_digest);
}

// Validate and sanitize user data for registration
function validate_and_sanitize_user_data($user_name, $email, $password)
{
  // Sanitize inputs
  $user_name = secure_input($user_name);
  $email = secure_input(strtolower($email));
  $password = secure_input($password);

  return [$user_name, $email, $password];
}
