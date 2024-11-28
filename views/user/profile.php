<!-- views/user/profile.php -->

<?php
$user = get_user_details(); // Retrieve user details
if ($user) {
  echo "<h1>Welcome, " . htmlspecialchars($user['user_name']) . "!</h1>";
  echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";

  // Edit user details form
  echo "<h3>Edit your details</h3>";
  echo "<form method='POST' action='index.php?action=edit_user'>";
  echo "<input type='hidden' name='user_id' value='" . $user['user_id'] . "'>";
  echo "<label for='new_user_name'>New Username:</label>";
  echo "<input type='text' id='new_user_name' name='new_user_name' value='" . htmlspecialchars($user['user_name']) . "' required><br>";

  echo "<label for='new_email'>New Email:</label>";
  echo "<input type='email' id='new_email' name='new_email' value='" . htmlspecialchars($user['email']) . "' required><br>";

  echo "<label for='new_password'>New Password (optional):</label>";
  echo "<input type='password' id='new_password' name='new_password'><br>";

  echo "<button type='submit'>Save Changes</button>";
  echo "</form>";

  // Form to handle account deletion (soft delete)
  echo "<form method='POST' action='index.php?action=profile'>"; // Make sure it posts back to profile action
  echo "<input type='hidden' name='delete_user_id' value='" . $user['user_id'] . "'>"; // Use the same name expected in the controller
  echo "<button type='submit' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete your account?\")'>Delete Account</button>";
  echo "</form>";

  echo "<a href='index.php?action=logout'>Logout</a>";
} else {
  echo "<h1>No user found</h1>";
  echo "<p>You must be logged in to view your profile. <a href='index.php?action=login'>Login here</a></p>";
}
?>