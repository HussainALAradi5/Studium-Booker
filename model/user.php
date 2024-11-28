<?php
require_once '../auth/security.php'; // Include security functions for validation

// Register a new user
function register_user($user_name, $email, $password)
{
  try {
    // Validate and sanitize inputs using security.php
    list($user_name, $email, $password) = validate_and_sanitize_user_data($user_name, $email, $password);

    // Check if user already exists (using function from security.php)
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

    // Fetch user based on email or username (using function from security.php)
    $user = fetch_user_by_identifier($identifier);

    // Check if user exists and verify password (using function from security.php)
    if (!$user || !verify_password($password, $user['password_digest'])) {
      return ['success' => false, 'message' => 'Invalid login credentials.'];
    }

    // Set cookies for the authenticated user
    set_user_cookie($user['user_id'], $user['is_admin']);

    return ['success' => true, 'message' => 'Login successful.', 'user' => $user];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
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

// Get details of a specific user (using user_id)
function get_user_details($user_id = null)
{
  try {
    // If no user_id is provided, get it from cookies
    if ($user_id === null && isset($_COOKIE['user_id'])) {
      $user_id = secure_input($_COOKIE['user_id']);
    }

    // If there's no user_id, return null (no user logged in)
    if (!$user_id) {
      return null;
    }

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

// Get details of all users (active users only)
function get_all_users()
{
  try {
    global $pdo;
    $sql = "SELECT * FROM user WHERE is_active = 1";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
