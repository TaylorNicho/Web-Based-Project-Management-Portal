<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Process form submission to create a project
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST['projectName'];
    $projectDescription = $_POST['projectDescription'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    // Logged-in user's ID
    $creatorUserID = $_SESSION['userID'];
    
    // Database connection
    include 'dbconnect.php';
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare and execute SQL insert project into table
    $sql = "INSERT INTO Projects (ProjectName, ProjectDescription, StartDate, EndDate, CreatorUserID) VALUES ('$projectName', '$projectDescription', '$startDate', '$endDate', '$creatorUserID')";
    if ($conn->query($sql) === TRUE) {
        $projectID = $conn->insert_id;
        
        // Insert user 
        $sql_insert_user = "INSERT INTO ProjectUsers (UserID, ProjectID) VALUES (?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("ii", $creatorUserID, $projectID);
        $stmt_insert_user->execute();
        
        echo "Project created successfully";
        header("Location: dashboard.php");
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Project</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h2>Create Project</h2>
    <form action="create_project.php" method="POST">
      <label for="projectName">Project Name:</label><br>
      <input type="text" id="projectName" name="projectName" required><br>
      <label for="projectDescription">Project Description:</label><br>
      <textarea id="projectDescription" name="projectDescription" required></textarea><br>
      <label for="startDate">Start Date:</label><br>
      <input type="date" id="startDate" name="startDate" required><br>
      <label for="endDate">End Date:</label><br>
      <input type="date" id="endDate" name="endDate" required><br><br>
      <button type="submit">Create Project</button>
    </form>
  </div>
</body>
</html>
