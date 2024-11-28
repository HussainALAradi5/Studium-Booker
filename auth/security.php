<?php
require_once '../db/database.php'; // Ensure database connection is established

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

// Register a new user
function register_user($user_name, $email, $password)
{
  try {
    // Secure inputs
    $user_name = secure_input($user_name);
    $email = secure_input(strtolower($email));

    // Check if user already exists (reuse the function from above)
    if (user_exists($email, $user_name)) {
      return ['success' => false, 'message' => 'User already exists.'];
    }

    // Hash the password
    $password_digest = password_hash($password, PASSWORD_BCRYPT);

    // SQL query to insert new user
    $sql = "INSERT INTO user (user_name, email, password_digest) VALUES (?, ?, ?)";

    // Prepare and execute the query
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_name, $email, $password_digest]);

    return ['success' => true, 'message' => 'User registered successfully.'];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Login user with email or username
function login_user($identifier, $password)
{
  try {
    // Secure the input identifier
    $identifier = secure_input(strtolower($identifier));

    // Fetch user based on email or username (reuse function from above)
    $user = fetch_user_by_identifier($identifier);

    // Check if user exists and verify password
    if (!$user || !password_verify($password, $user['password_digest'])) {
      return ['success' => false, 'message' => 'Invalid login credentials.'];
    }

    // Set cookies for the authenticated user
    set_user_cookie($user['user_id'], $user['is_admin']);

    return ['success' => true, 'message' => 'Login successful.', 'user' => $user];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Get user details from the database (using cookies)
function get_user_details()
{
  if (!isset($_COOKIE['user_id'])) {
    return null;
  }

  try {
    // Secure the user_id from the cookie
    $user_id = secure_input($_COOKIE['user_id']);

    // SQL query to fetch user details
    $sql = "SELECT * FROM user WHERE user_id = ? AND is_active = 1";

    // Prepare and execute the query
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    return null;
  }
}

// Soft delete user (set is_active to false)
function delete_user($user_id)
{
  try {
    // SQL query to soft delete a user
    $sql = "UPDATE user SET is_active = 0 WHERE user_id = ?";

    // Prepare and execute the query
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);

    return ['success' => true, 'message' => 'User deleted successfully.'];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Set authentication cookies for the user
function set_user_cookie($user_id, $is_admin)
{
  setcookie("user_id", $user_id, time() + 3600, "/", "", false, true);
  setcookie("is_admin", $is_admin, time() + 3600, "/", "", false, true);
}
