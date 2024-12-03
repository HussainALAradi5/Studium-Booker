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
// View all studiums
function view_studiums()
{
  global $pdo;

  $sql = "SELECT * FROM studium";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all studiums as an associative array
}
//view only one studium by its id
function view_studium($studium_id)
{
  global $pdo;

  $sql = "SELECT * FROM studium WHERE studium_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id]);

  return $stmt->fetch(PDO::FETCH_ASSOC); // Return a single studium as an associative array
}
