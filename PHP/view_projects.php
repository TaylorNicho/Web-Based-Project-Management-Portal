<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Projects</title>
  <link rel="stylesheet" href="styles.css"> 
  <style>
    .container {
      max-width: 600px;
      margin: 0 auto;
    }

    h2 {
      text-align: center;
    }

    .project-list {
      list-style-type: none;
      padding: 0;
    }

    .project-list li {
      margin-bottom: 10px;
    }

    .project-list button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .project-list button:hover {
      background-color: #0056b3;
    }

    .dashboard-button {
      display: block;
      margin-top: 20px;
      padding: 10px;
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      transition: background-color 0.3s ease;
    }

    .dashboard-button:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Projects</h2>
    <ul class="project-list">
      <?php
      session_start();

      // Check if user is logged in
      if (!isset($_SESSION['userID'])) {
          header("Location: login.php");
          exit();
      }

      // database connection
      include 'dbconnect.php';
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Get user ID
      $userID = $_SESSION['userID'];

      // get projects assigned to the user
      $sql_projects = "SELECT ProjectID, ProjectName FROM Projects WHERE ProjectID IN (SELECT ProjectID FROM ProjectUsers WHERE UserID = ?)";
      $stmt_projects = $conn->prepare($sql_projects);
      $stmt_projects->bind_param("i", $userID);
      $stmt_projects->execute();
      $result_projects = $stmt_projects->get_result();

      if ($result_projects->num_rows > 0) {
          while ($row = $result_projects->fetch_assoc()) {
              // Output each project as a list
              echo '<li>';
              echo '<form action="project_details.php" method="POST">';
              echo '<button type="submit" name="projectID" value="' . $row["ProjectID"] . '">' . $row["ProjectName"] . '</button>';
              echo '</form>';
              echo '</li>';
          }
      } else {
          echo "<li>No projects found.</li>";
      }

      // Close connection
      $stmt_projects->close();
      $conn->close();
      ?>
    </ul>

    <!-- Button to go back -->
    <form action="dashboard.php">
      <button type="submit" class="dashboard-button">Back to Dashboard</button>
    </form>
  </div>
</body>
</html>
