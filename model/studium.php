<?php
require_once './auth/security.php';

// Add a new studium
function add_studium($studium_name, $width, $height, $location, $price_per_hour)
{
  global $pdo;

  // Ensure the user is an admin
  validate_admin_logged_in();

  $sql = "INSERT INTO studium (studium_name, width, height, location, price_per_hour) 
            VALUES (?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([secure_input($studium_name), $width, $height, secure_input($location), $price_per_hour]);

  return "Studium added successfully.";
}

// Remove an existing studium by ID
function remove_studium($studium_id)
{
  global $pdo;

  // Ensure the user is an admin
  validate_admin_logged_in();

  $sql = "DELETE FROM studium WHERE studium_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return "Studium removed successfully.";
}
