<form method="POST" action="index.php?action=register">
  <h2>Register</h2>
  <label for="user_name">Username:</label>
  <input type="text" name="user_name" id="user_name" required>
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" required>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" required>
  <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="index.php?action=login">Login here</a></p>