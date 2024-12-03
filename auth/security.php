<?php
require_once './db/database.php'; // Ensure database connection is established
session_start(); // Start the session

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
function is_admin($user_id)
{
  global $pdo;

  $sql = "SELECT is_admin FROM user WHERE user_id = ? AND is_active = 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);

  return $stmt->fetchColumn() === "1"; // Return true if the user is admin
}

// Validate user authentication
function validate_user_logged_in()
{
  if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    throw new Exception("User is not logged in.");
  }
  return $_SESSION['user_id'] ?? $_COOKIE['user_id'];
}

// Validate admin authentication
function validate_admin_logged_in()
{
  $user_id = validate_user_logged_in();
  if (
    !(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) &&
    !(isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == 1)
  ) {
    throw new Exception("Admin privileges required.");
  }
  return $user_id;
}
