<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>

  <link rel="stylesheet" href="css/profile.css">

</head>

<body>
  <?php
  if (isset($_GET['action']) && $_GET['action'] === 'edit') {
    require 'edit.php';
  } else {
    require 'user_details.php';
  }
  ?>