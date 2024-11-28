<?php
try {
  // Database connection details
  $host = "localhost";
  $dbname = "studium_booker";
  $user = "root";

  // Connect to MySQL
  $pdo = new PDO("mysql:host=$host", $user);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Create database if it does not exist
  $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
  $pdo->exec("USE $dbname");

  // Define table creation queries in variables

  // User table (must be created first since it's referenced by other tables)
  $user = "
    CREATE TABLE IF NOT EXISTS user (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password_digest VARCHAR(255) NOT NULL,
        is_admin BOOLEAN NOT NULL DEFAULT 0,
        is_active BOOLEAN NOT NULL DEFAULT 1
    )";

  // Execute the query for creating the user table
  $pdo->exec($user);

  // Studium table (foreign key to user table should be valid now)
  $studium = "
    CREATE TABLE IF NOT EXISTS studium (
        studium_id INT AUTO_INCREMENT PRIMARY KEY,
        studium_name VARCHAR(255) NOT NULL,
        width DECIMAL(10, 2) NOT NULL,
        height DECIMAL(10, 2) NOT NULL,
        location VARCHAR(255) NOT NULL,
        price_per_hour DECIMAL(10, 2) NOT NULL,
        is_occupied BOOLEAN NOT NULL DEFAULT 0,
        occupied_by INT,
        FOREIGN KEY (occupied_by) REFERENCES user(user_id)
    )";

  // Execute the query for creating the studium table
  $pdo->exec($studium);

  // Reservation table
  $reservation = "
  CREATE TABLE IF NOT EXISTS reservation (
      user_id INT NOT NULL,
      studium_id INT NOT NULL,
      total_area DECIMAL(10, 2),
      start_at DATETIME NOT NULL,  
      end_at DATETIME NOT NULL,
      total_occupied_time DECIMAL(10, 2),
      price_per_hour DECIMAL(10, 2),
      total_price DECIMAL(10, 2),
      PRIMARY KEY (user_id, studium_id),
      FOREIGN KEY (user_id) REFERENCES user(user_id),
      FOREIGN KEY (studium_id) REFERENCES studium(studium_id)
  )";


  // Execute the query for creating the reservation table
  $pdo->exec($reservation);

  // Rating table
  $rating = "
    CREATE TABLE IF NOT EXISTS rating (
        rate_id INT AUTO_INCREMENT PRIMARY KEY,
        rate INT NOT NULL,
        studium_id INT NOT NULL,
        rated_by_user INT NOT NULL,
        rated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (studium_id) REFERENCES studium(studium_id),
        FOREIGN KEY (rated_by_user) REFERENCES user(user_id)
    )";

  // Execute the query for creating the rating table
  $pdo->exec($rating);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
