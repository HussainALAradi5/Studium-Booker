<?php
// Fetch and validate studium ID from the URL
$studium_id = $_GET['id'] ?? null;
if (!$studium_id || !($studium = view_studium($studium_id))) {
  die("Invalid studium ID.");
}

// Fetch related data
$average_rating = get_average_rating($studium_id);
$comments = get_comments_by_studium($studium_id);
$ratings = get_all_ratings($studium_id);

// Check user authentication
$is_logged_in = false;
$user_id = null;
$user_comment = null;

try {
  $user_id = validate_user_logged_in();
  $is_logged_in = true;
  $user_comment = user_has_commented($studium_id, $user_id);
} catch (Exception $e) {
  // User not logged in
}

// Handle POST comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && $is_logged_in) {
  $comment = secure_input($_POST['comment']);
  $response = add_comment($studium_id, $comment);
  echo "<script>alert('" . addslashes($response) . "');</script>";
  header("Location: " . $_SERVER['REQUEST_URI']);
  exit;
}

// Handle POST rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && $is_logged_in) {
  $rating = intval($_POST['rating']);
  try {
    add_rating($studium_id, $rating);
    echo "<script>alert('Rating added successfully.');</script>";
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
  } catch (Exception $e) {
    echo "<script>alert('" . addslashes($e->getMessage()) . "');</script>";
  }
}

// Check if the user has already rated the studium
$user_rating = get_user_rating($studium_id, $user_id);
$has_rated = $user_rating !== false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($studium['studium_name']); ?> - Details</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/studium.css?v=<?php echo time(); ?>">
  <style>
    .star-rating {
      color: #ffc107;
      /* Change star color */
    }

    .card-body {
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8 mb-5">
        <div class="card mb-4">
          <div class="card-body">
            <h1 class="card-title"><?php echo htmlspecialchars($studium['studium_name']); ?></h1>
            <p class="card-text">Location: <?php echo htmlspecialchars($studium['location']); ?></p>
            <p class="card-text">Price per hour: <?php echo htmlspecialchars($studium['price_per_hour']); ?> BD</p>
            <div class="text-center mb-3">
              <h5>Average Rating:
                <?php for ($i = 0; $i < 5; $i++): ?>
                  <span class="bi <?php echo $i < $average_rating ? 'bi-star-fill star-rating' : 'bi-star star-rating'; ?>"></span>
                <?php endfor; ?>
              </h5>
            </div>

            <?php include 'reservation.php'; ?>
            <?php include 'rating.php'; ?>
            <?php include 'comment.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>