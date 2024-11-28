<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link rel="stylesheet" href="css/login_register.css">

</head>

<body>
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