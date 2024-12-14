<?php
$user = get_user_details();
if ($user) :
?>

  <div class="container mt-4">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($user['user_name']); ?></h5>
          <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
          <p class="card-text"><strong>Status:</strong> <?php echo $user['is_active'] == 1 ? 'Active' : 'Inactive'; ?></p>
          <a href="index.php?action=edit" class="btn btn-primary">Edit Profile</a>
        </div>
      </div>
    </div>
  </div>

<?php else : ?>
  <p class="text-center">User details not found. Please <a href="index.php?action=login">Login</a> to view your profile.</p>
<?php endif; ?>