<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .container {
      max-width: 600px;
      margin: 0 auto;
    }

    h2 {
      text-align: center;
    }

    .options {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
    }

    .options a {
      text-decoration: none;
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .options a i {
      font-size: 36px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <div class="options">
      <a href="view_projects.php">
        <i class="fas fa-folder-open"></i>
        <span>View Projects</span>
      </a>
      <a href="create_project.php">
        <i class="fas fa-plus-circle"></i>
        <span>Create New Project</span>
      </a>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>
</body>
</html>
