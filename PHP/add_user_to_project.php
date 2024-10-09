<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get project ID and username
    $projectID = $_POST['projectID'];
    $inputUsername = $_POST['username']; 
    
    if(!isset ($_SESSION['projectID']) || !empty($_POST['projectID'])) {

        $_SESSION['projectID'] = $projectID;

    }

    $projectID = $_POST['projectID'];

    // Database connection 
    include 'dbconnect.php';
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare SQL statement 
    $sql = "SELECT UserID FROM Users WHERE UserName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists, get their UserID
        $row = $result->fetch_assoc();
        $userID = $row['UserID'];
        
        //Check if user is already added to the project
        $sql = "SELECT * FROM ProjectUsers WHERE ProjectID = ? AND UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $projectID, $userID);
        $stmt->execute();
        $result = $stmt->get_result(); 
        

        if ($result->num_rows == 0) {
            // User is not already added insert 
            $sql = "INSERT INTO ProjectUsers (ProjectID, UserID) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $projectID, $userID);
            
            if ($stmt->execute()) {
                echo "User added to project successfully!";
            } else {
                echo "Error adding user to project: " . $stmt->error;
            }
        } else {
            echo "User is already added to the project.";
        }
    } else {
        echo "User not found.";
    }

    // Close database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add User to Project</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .button-container {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Add User to Project</h2>
    <form action="add_user_to_project.php?projectID=<?php echo $_POST['projectID']; ?>" method="post">
      <input type="hidden" name="projectID" value="<?php echo $_POST['projectID']; ?>">
      <label for="username">User Name:</label>
      <input type="text" id="username" name="username" required>
      
      <button type="submit">Add User</button>
    </form>
    <!-- Button to return to project details page -->
    <div class="button-container">
      <form action="project_details.php" method="POST">
        <input type="hidden" name="projectID" value="<?php echo $_POST['projectID']; ?>">
        <button type="submit">Return to Project Details</button>
      </form>
    </div>
  </div>
</body>
</html>
