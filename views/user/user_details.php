<?php
$user = get_user_details();
if ($user) {
  echo "<h1>Welcome, " . htmlspecialchars($user['user_name']) . "!</h1>";
  echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
} else {
  echo "<h1>No user found</h1>";
  echo "<p>You must be logged in to view your profile. <a href='index.php?action=login'>Login here</a></p>";
}
