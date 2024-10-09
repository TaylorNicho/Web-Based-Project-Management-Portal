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

    // Check task ID 
    if (isset($_POST['taskID'])) {
        // Get task ID 
        $taskID = $_POST['taskID'];
        $projectID = $_POST['projectID'];

        // Update task status to To Do
        $sql = "UPDATE Tasks SET Status = 'To Do' WHERE TaskID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $taskID);
        
        if ($stmt->execute()) {
            $_SESSION['projectID'] = $projectID;
            header("Location: view_done_tasks.php");
            exit();
    
        } else {
            echo "Error marking task as to do: " . $conn->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Task ID not provided.";
    }

    // Close connection
    $conn->close();

?>
