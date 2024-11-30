<?php
$user = get_user_details();
if ($user) :
?>

  <div class="profile-card">

    <h3><?php echo htmlspecialchars($user['user_name']); ?></h3>

    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

    <p><strong>Status:</strong> <?php echo $user['is_active'] == 1 ? 'Active' : 'Inactive'; ?></p>

    <a href="index.php?action=edit">
      <button>Edit Profile</button>
    </a>
  </div>

<?php else : ?>
  <p>User details not found. Please <a href="index.php?action=login">Login</a> to view your profile.</p>
<?php endif; ?>