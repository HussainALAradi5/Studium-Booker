<?php
if (isset($_COOKIE['user_id'])) {
  echo "<h1>Welcome to the Home Page</h1>";
  echo "<p>You're logged in.</p>";
  echo "<a href='index.php?action=profile'>Go to your profile</a>";
  echo "<a href='index.php?action=logout'>Logout</a>";
} else {
  echo "<h1>Welcome to the Home Page</h1>";
  echo "<p>Please <a href='index.php?action=login'>Login</a> or <a href='index.php?action=register'>Register</a></p>";
}
