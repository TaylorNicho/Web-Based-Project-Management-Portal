<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Members</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .member {
      margin-bottom: 20px; 
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Initialize variables
    $projectID = null;
    $stmt = null;

    // get user IDs added to the project
    if (isset($_POST['projectID'])) {
        $projectID = $_POST['projectID'];

        // SQL statement to get user IDs
        $sql = "SELECT UserID FROM ProjectUsers WHERE ProjectID = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $projectID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if any users are found
            if ($result->num_rows > 0) {
                echo "<h2>Project Members</h2>";
                echo "<ul>";
                
                while ($row = $result->fetch_assoc()) {
                    $userID = $row['UserID'];
                    // Fetch username 
                    $sql_username = "SELECT UserName FROM Users WHERE UserID = ?";
                    $stmt_username = $conn->prepare($sql_username);
                    if ($stmt_username) {
                        $stmt_username->bind_param("i", $userID);
                        $stmt_username->execute();
                        $result_username = $stmt_username->get_result();
                        $row_username = $result_username->fetch_assoc();
                        $username = $row_username['UserName'];


                        echo "<li class='member'>";
                        echo '<form action="member_details.php" method="POST">';
                        echo "<input type='hidden' name='userID' value=$userID>";
                        echo '<button type="submit" name="projectID" class="button add-button" value="' . $projectID . '"><i class="fas fa-arrow-left"></i> ' . $username . '</button>';
                        echo '</form>';

                        echo '<form action="add_notes.php" method="POST">';
                        echo "<input type='hidden' name='userID' value=$userID>";
                        echo '<button type="submit" name="projectID" class="button add-button" value="' . $projectID . '"><i class="fas fa-arrow-left"></i> Add Note </button>';
                        echo '</form>';
                        echo '</li>';

                        $stmt_username->close(); 
                    }
                }
                echo "</ul>";
            } else {
                echo "No members found for this project.";
            }
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    }

    if ($stmt) {
        $stmt->close();
    }

    // Close database connection
    $conn->close();
    ?>

    <!-- Button to return to project details page -->
    <form action="project_details.php" method="POST">
        <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
        <button type="submit">Return to Project Details</button>
    </form>

  </div>
</body>
</html>
