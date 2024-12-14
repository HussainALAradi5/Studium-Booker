<?php
require_once('./views/headers.php');

// Handle actions (by default, show home page)
$action = $_GET['action'] ?? 'home';

switch ($action) {
  case 'register':
    // If the form is submitted (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $user_name = $_POST['user_name'] ?? '';
      $email = $_POST['email'] ?? '';
      $password = $_POST['password'] ?? '';

      // Call register function from user.php
      $response = register_user($user_name, $email, $password);

      // If registration is successful, redirect to login page
      if ($response['success']) {
        header("Location: index.php?action=login"); // Redirect to login page
        exit;
      }

      echo $response['message']; // Show error message if registration fails
    }
    include 'views/user/register.php'; // Show registration form
    break;

  case 'login':
    // If the form is submitted (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $identifier = $_POST['identifier'] ?? '';
      $password = $_POST['password'] ?? '';

      // Call login function from user.php
      $response = login_user($identifier, $password);

      // If login is successful, redirect to profile page
      if ($response['success']) {
        header("Location: index.php?action=profile"); // Redirect to profile page
        exit;
      }

      echo $response['message']; // Show error message if login fails
    }
    include 'views/user/login.php'; // Show login form
    break;

  case 'profile':
    // Get user details (fetch user info)
    $user = get_user_details();

    // If user is not logged in, redirect to login page
    if (!$user) {
      header("Location: index.php?action=login"); // Redirect to login if not logged in
      exit;
    }

    // Handle delete user form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
      $user_id = $_POST['delete_user_id'];
      $response = delete_user($user_id); // Delete user via the model
      echo $response['message']; // Show deletion message
      if ($response['success']) {
        header("Location: index.php?action=home"); // Redirect to home after deletion
        exit;
      }
    }

    include 'views/user/profile.php'; // Show profile page
    break;

  case 'logout':
    // Call logout function to clear cookies
    logout_user();
    header("Location: index.php?action=home"); // Redirect to home page after logout
    exit;

  case 'home':
    // Default case to load the home page
  default:
    include 'views/home.php'; // Show home page by default
    break;

  case 'add_studium':
    // Ensure the user is an admin
    if (!isset($_COOKIE['user_id']) || $_COOKIE['is_admin'] != 1) {
      // Redirect if not an admin
      header("Location: index.php?action=home");
      exit;
    }


    include 'views/studium/add_studium.php'; // Include the form to add a studium
    break;
  case 'studium':
    // Get the studium ID from the query parameters
    $studium_id = $_GET['id'] ?? null;

    if (!$studium_id) {
      echo "Studium ID not provided.";
      break;
    }

    // Fetch studium details using the model
    $studium = view_studium($studium_id);

    if (!$studium) {
      echo "Studium not found.";
      break;
    }

    // Get average rating (optional, if the function exists in your rating model)
    $average_rating = get_average_rating($studium_id);

    // Include the studium view to display the details
    include './views/studium/studium.php';
    break;
}

?>
<?php include('./views/footer.php'); ?>
