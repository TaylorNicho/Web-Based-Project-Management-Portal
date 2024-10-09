<?php
// Database connection settings
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Check projectID 
if (isset($_POST['projectID'])) {
    $projectID = $_POST['projectID'];

    // Fetch usernames of members in the project
    $sql = "SELECT u.UserName FROM Users u JOIN ProjectUsers pu ON u.UserID = pu.UserID WHERE pu.ProjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectID);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $usernames = array();

        while ($row = $result->fetch_assoc()) {
            $usernames[] = $row['UserName'];
        }

        // Close statement
        $stmt->close();

        // Close database connection
        $conn->close();

        // Return the array of usernames
        return $usernames;
    } else {
        echo "Error fetching members: " . $stmt->error;
    }
} else {
    echo "Project ID not provided";
}
?>
