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

// Check task ID
if (isset($_POST['taskID'])) {
    $taskID = $_POST['taskID'];
    $projectID = $_POST['projectID'];

    // Update status to Done
    $sql = "UPDATE Tasks SET Status = 'Done' WHERE TaskID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskID);
    
    if ($stmt->execute()) {
        $_SESSION['projectID'] = $projectID;
        header("Location: view_tasks.php");
        exit();

    } else {
        echo "Error marking task as done: " . $conn->error;
    }

    // Close
    $stmt->close();

} else {
    echo "Task ID not provided.";
}
// Close
$conn->close();
?>


