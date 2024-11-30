<?php
// Ensure the user is logged in and is an admin
require_once 'auth/security.php';

if (!isset($_COOKIE['user_id']) || $_COOKIE['is_admin'] != 1) {
  // Redirect non-admin users away from this page
  header("Location: index.php?action=home");
  exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Handle the form submission for adding a studium
  $studium_name = secure_input($_POST['studium_name']);
  $width = secure_input($_POST['width']);
  $height = secure_input($_POST['height']);
  $location = secure_input($_POST['location']);
  $price_per_hour = secure_input($_POST['price_per_hour']);

  // Add studium logic (You would call your add_studium function here)
  $message = add_studium($studium_name, $width, $height, $location, $price_per_hour);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Studium</title>
  <link rel="stylesheet" href="css/admin_panel.css">
</head>

<body>
  <header>
    <h1>Add Studium</h1>
  </header>
  <main>
    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <section>
      <h2>Add New Studium</h2>
      <form method="POST" action="add_studium.php">
        <label for="studium_name">Studium Name:</label>
        <input type="text" name="studium_name" id="studium_name" required>

        <label for="width">Width (m):</label>
        <input type="number" name="width" id="width" required>

        <label for="height">Height (m):</label>
        <input type="number" name="height" id="height" required>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" required>

        <label for="price_per_hour">Price per Hour:</label>
        <input type="number" name="price_per_hour" id="price_per_hour" required>

        <button type="submit">Add Studium</button>
      </form>
    </section>
  </main>
</body>

</html>