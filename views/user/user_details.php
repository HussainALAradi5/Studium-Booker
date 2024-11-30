<?php
$user = get_user_details();
if ($user) {

  echo "<h1>Welcome, " . htmlspecialchars($user['user_name']) . "!</h1>";
  echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
  echo "<p>User Name: " . htmlspecialchars($user['user_name']) . "</p>";
  if ($user['is_active'] == 1)
    echo "<p>Status: Active </p>";
  else
    echo "<p>Status: InActive </p>";
} else {
  echo "<h1>No user found</h1>";
  echo "<p>You must be logged in to view your profile. <a href='index.php?action=login'>Login here</a></p>";
}
