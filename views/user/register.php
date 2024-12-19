<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h2 class="text-center mb-4">Register</h2>
            <form method="POST" action="index.php?action=register">
              <div class="form-group">
                <label for="user_name">Username:</label>
                <input type="text" class="form-control" name="user_name" id="user_name" required>
              </div>

              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>

              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" required>
              </div>

              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>

            <p class="text-center mt-3">Already have an account? <a href="index.php?action=login">Login here</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>