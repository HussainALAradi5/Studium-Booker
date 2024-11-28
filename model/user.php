<?php
require_once '../db/database.php'; // Ensure database connection is established

// Function to register a user
function register_user($user_name, $email, $password)
{
  global $pdo;

  // Clean inputs
  $user_name = secure_input($user_name);
  $email = secure_input(strtolower($email));

  // Check if user already exists
  $sql = "SELECT COUNT(*) FROM user WHERE LOWER(email) = ? OR LOWER(user_name) = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email, strtolower($user_name)]);

  if ($stmt->fetchColumn() > 0) {
    return ['success' => false, 'message' => 'User already exists.'];
  }

  // Hash the password
  $password_digest = password_hash($password, PASSWORD_BCRYPT);

  // Insert new user
  $sql = "INSERT INTO user (user_name, email, password_digest) VALUES (?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_name, $email, $password_digest]);

  return ['success' => true, 'message' => 'User registered successfully.'];
}

// Function to login a user
function login_user($identifier, $password)
{
  global $pdo;

  // Clean input
  $identifier = secure_input(strtolower($identifier));

  // Fetch user by email or username
  $sql = "SELECT * FROM user WHERE LOWER(email) = ? OR LOWER(user_name) = ? AND is_active = 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$identifier, $identifier]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$user || !password_verify($password, $user['password_digest'])) {
    return ['success' => false, 'message' => 'Invalid login credentials.'];
  }

  // Set session or cookies for authentication
  set_user_cookie($user['user_id'], $user['is_admin']);

  return ['success' => true, 'message' => 'Login successful.', 'user' => $user];
}

// Function to get user details based on the user_id from cookies
function get_user_details()
{
  if (!isset($_COOKIE['user_id'])) {
    return null;
  }

  global $pdo;

  // Get the user ID from the cookie
  $user_id = secure_input($_COOKIE['user_id']);
  $sql = "SELECT * FROM user WHERE user_id = ? AND is_active = 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);

  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to delete a user (soft delete)
function delete_user($user_id)
{
  global $pdo;

  // Mark user as inactive (soft delete)
  $sql = "UPDATE user SET is_active = 0 WHERE user_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id]);

  return ['success' => true, 'message' => 'User deleted successfully.'];
}

// Function to set user authentication cookies
function set_user_cookie($user_id, $is_admin)
{
  setcookie("user_id", $user_id, time() + 3600, "/", "", false, true);
  setcookie("is_admin", $is_admin, time() + 3600, "/", "", false, true);
}
