<?php

function is_studium_reserved($studium_id, $start_at, $end_at)
{
  global $pdo;

  $sql = "SELECT COUNT(*)
FROM reservation
WHERE studium_id = ?
AND (
(start_at <= ? AND end_at> ?) OR
  (start_at < ? AND end_at>= ?)
    )";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id, $end_at, $start_at, $end_at, $start_at]);
  return $stmt->fetchColumn() > 0; // Returns true if a conflict exists
}

function get_available_studiums($start_at, $end_at)
{
  global $pdo;

  $all_studiums = view_studiums(); // Reuse the `view_studiums` function
  $available_studiums = [];

  foreach ($all_studiums as $studium) {
    if (!is_studium_reserved($studium['studium_id'], $start_at, $end_at)) {
      $available_studiums[] = $studium;
    }
  }

  return $available_studiums; // Return studiums that are free during the given period
}
function get_studium_occupant($studium_id, $start_at, $end_at)
{
  global $pdo;

  $sql = "SELECT user_id 
            FROM reservation 
            WHERE studium_id = ? 
            AND (
                (start_at <= ? AND end_at > ?) OR 
                (start_at < ? AND end_at >= ?)
            )";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$studium_id, $end_at, $start_at, $end_at, $start_at]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result ? $result['user_id'] : null; // Return user_id or null if not occupied
}
function calculate_total_price($price_per_hour, $total_hours)
{
  return $price_per_hour * $total_hours;
}
function calculate_total_hours($start_at, $end_at)
{
  $start = new DateTime($start_at);
  $end = new DateTime($end_at);

  $interval = $start->diff($end);
  return ($interval->days * 24) + $interval->h + ($interval->i > 0 ? 1 : 0); // Add 1 for partial hours
}
function reserve_studium($studium_id, $start_at, $end_at)
{
  global $pdo;

  // Validate that the user is logged in
  $user_id = validate_user_logged_in();

  // Secure inputs
  $studium_id = secure_input($studium_id);
  $start_at = secure_input($start_at);
  $end_at = secure_input($end_at);

  // Check if the user already has a reservation during this time
  $user_reservation = get_studium_occupant($studium_id, $start_at, $end_at);
  if ($user_reservation && $user_reservation === $user_id) {
    return ['success' => false, 'message' => 'You already have a reservation during this time.'];
  }

  // Check if the studium is already reserved
  if (is_studium_reserved($studium_id, $start_at, $end_at)) {
    return ['success' => false, 'message' => 'Studium is already reserved during this time.'];
  }

  // Retrieve the studium details
  $studium = view_studium($studium_id); // Assuming `view_studium` retrieves a single studium by ID
  if (!$studium) {
    return ['success' => false, 'message' => 'Invalid studium ID.'];
  }

  // Calculate the total hours and price
  $total_hours = calculate_total_hours($start_at, $end_at);
  $total_price = calculate_total_price($studium['price_per_hour'], $total_hours);

  // Insert the reservation into the database
  $sql = "INSERT INTO reservation (user_id, studium_id, start_at, end_at, total_hours, price_per_hour, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user_id, $studium_id, $start_at, $end_at, $total_hours, $studium['price_per_hour'], $total_price]);

  return ['success' => true, 'message' => 'Reservation successful.'];
}
