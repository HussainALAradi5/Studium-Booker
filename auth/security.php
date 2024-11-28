<?php
require_once '../db/database.php'; // Ensure database connection is established

function secure_input($input)
{
  return htmlspecialchars(trim($input));
}

// Register function
function register_user($user_name, $email, $password)
{
  global $pdo;

  try {
    $user_name = secure_input($user_name);
    $email = secure_input(strtolower($email));

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE LOWER(email) = ? OR LOWER(user_name) = ?");
    $stmt->execute([$email, strtolower($user_name)]);

    if ($stmt->fetchColumn() > 0) {
      return ['success' => false, 'message' => 'User already exists.'];
    }

    // Hash the password
    $password_digest = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO user (user_name, email, password_digest) VALUES (?, ?, ?)");
    $stmt->execute([$user_name, $email, $password_digest]);

    return ['success' => true, 'message' => 'User registered successfully.'];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Login function
function login_user($identifier, $password)
{
  global $pdo;

  try {
    $identifier = secure_input(strtolower($identifier));

    // Fetch user by email or user_name
    $stmt = $pdo->prepare("SELECT * FROM user WHERE LOWER(email) = ? OR LOWER(user_name) = ? AND is_active = 1");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password_digest'])) {
      return ['success' => false, 'message' => 'Invalid login credentials.'];
    }

    // Set session or cookies as needed for authentication
    set_user_cookie($user['user_id'], $user['is_admin']);

    return ['success' => true, 'message' => 'Login successful.', 'user' => $user];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Delete user (soft delete)
function delete_user($user_id)
{
  global $pdo;

  try {
    $stmt = $pdo->prepare("UPDATE user SET is_active = 0 WHERE user_id = ?");
    $stmt->execute([$user_id]);

    return ['success' => true, 'message' => 'User deleted successfully.'];
  } catch (PDOException $e) {
    return ['success' => false, 'message' => $e->getMessage()];
  }
}

// Get user details from cookies
function get_user_details()
{
  if (!isset($_COOKIE['user_id'])) {
    return null;
  }

  global $pdo;

  try {
    $user_id = secure_input($_COOKIE['user_id']);
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_id = ? AND is_active = 1");
    $stmt->execute([$user_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    return null;
  }
}

// Set user authentication cookie
function set_user_cookie($user_id, $is_admin)
{
  setcookie("user_id", $user_id, time() + 3600, "/", "", false, true);
  setcookie("is_admin", $is_admin, time() + 3600, "/", "", false, true);
}
