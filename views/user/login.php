<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/login_register.css">

</head>

<body>
  <form method="POST" action="index.php?action=login">
    <h2>Login</h2>
    <label for="identifier">Email or Username:</label>
    <input type="text" name="identifier" id="identifier" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
</body>

</html>