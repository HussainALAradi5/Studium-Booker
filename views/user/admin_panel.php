<?php



try {
  // Ensure the user is logged in and is an admin
  validate_admin_logged_in();

  // Handle form submissions for adding or removing studiums
  $message = "";
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_studium'])) {
      // Add studium logic
      $studium_name = secure_input($_POST['studium_name']);
      $width = secure_input($_POST['width']);
      $height = secure_input($_POST['height']);
      $location = secure_input($_POST['location']);
      $price_per_hour = secure_input($_POST['price_per_hour']);

      $message = add_studium($studium_name, $width, $height, $location, $price_per_hour);
    } elseif (isset($_POST['remove_studium'])) {
      // Remove studium logic
      $studium_id = secure_input($_POST['studium_id']);
      $message = remove_studium($studium_id);
    }
  }

  // Fetch all studiums to display in the panel
  $studiums = view_studiums();
} catch (Exception $e) {
  // Handle unauthorized access or other exceptions
  die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="css/admin_panel.css">

</head>

<body>
  <header>
    <h1>Admin Panel</h1>
  </header>
  <main>
    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Form to add a new studium -->
    <section>
      <h2>Add Studium</h2>
      <form method="POST" action="admin_panel.php">
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

        <button type="submit" name="add_studium">Add Studium</button>
      </form>
    </section>

    <!-- Form to remove an existing studium -->
    <section>
      <h2>Remove Studium</h2>
      <form method="POST" action="admin_panel.php">
        <label for="studium_id">Select Studium:</label>
        <select name="studium_id" id="studium_id" required>
          <?php foreach ($studiums as $studium): ?>
            <option value="<?= htmlspecialchars($studium['studium_id']); ?>">
              <?= htmlspecialchars($studium['studium_name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" name="remove_studium">Remove Studium</button>
      </form>
    </section>

    <!-- Display all studiums -->
    <section>
      <h2>All Studiums</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Width</th>
            <th>Height</th>
            <th>Location</th>
            <th>Price per Hour</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($studiums as $studium): ?>
            <tr>
              <td><?= htmlspecialchars($studium['studium_id']); ?></td>
              <td><?= htmlspecialchars($studium['studium_name']); ?></td>
              <td><?= htmlspecialchars($studium['width']); ?></td>
              <td><?= htmlspecialchars($studium['height']); ?></td>
              <td><?= htmlspecialchars($studium['location']); ?></td>
              <td><?= htmlspecialchars($studium['price_per_hour']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>
</body>

</html>