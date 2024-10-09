<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check if projectID is provided in the URL
if (!isset($_SESSION['projectID'])) {
    echo "Project ID not provided";
    exit();
}

// Get projectID from URL parameter
$projectID = $_SESSION['projectID'];

// Database connection settings
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch project details based on projectID from URL query parameter
if (isset($_POST['taskName'], $_POST['taskDescription'], $_POST['dueDate'], $_POST['projectID'])) {
    // Get task details from the form
    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $dueDate = $_POST['dueDate'];
    $projectID = $_POST['projectID'];

    // Prepare SQL statement 
    $sql = "INSERT INTO Tasks (TaskName, Description, DueDate, ProjectID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $taskName, $taskDescription, $dueDate, $projectID);

    // insert the task
    if ($stmt->execute()) {
        // Task inserted successfully
        $taskID = $conn->insert_id; 

        // Check if users are selected for assignment
        if (isset($_POST['selectedUsers'])) {
            $selectedUsers = $_POST['selectedUsers'];

            // Prepare SQL statement to insert task assignments into TaskAssignments table
            $sql_assign = "INSERT INTO TaskAssignments (TaskID, UserID, ProjectID) VALUES (?, ?, ?)";
            $stmt_assign = $conn->prepare($sql_assign);

            if ($stmt_assign) {
                // Loop through selected users and insert task assignments
                foreach ($selectedUsers as $userID) {
                    // Execute the prepared statement for each user
                    $stmt_assign->bind_param("iii", $taskID, $userID, $projectID);
                    if ($stmt_assign->execute()) {
                        // Task assignment inserted successfully
                    } else {
                        // Error occurred while inserting task assignment
                        echo "Error adding task assignment: " . $stmt_assign->error;
                    }
                }
                $stmt_assign->close();
            } else {
                // Error preparing statement for task assignment
                echo "Error preparing statement: " . $conn->error;
            }
        }

        // Redirect to the project details page or any other appropriate page
        header("Location: project_details.php?projectID=$projectID");
        exit();
    } else {
        // Error occurred while inserting task
        echo "Error adding task: " . $stmt->error;
    }
    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
