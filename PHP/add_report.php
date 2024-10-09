<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check projectID 
if (!isset($_POST['projectID'])) {
    echo "Project ID not provided";
    exit();
}

// Get projectID
$projectID = $_POST['projectID'];


if (isset($projectID)){
    // Retrieve form data
    $reportTitle = $_POST['ReportTitle'];
    $reportDescription = $_POST['ReportDescription'];
    $reportDate = date('Y-m-d'); 

    // Database connection 
    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Insert report 
    $sql = "INSERT INTO ProjectReports (ProjectID, ReportTitle, ReportDescription, ReportDate) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $projectID, $reportTitle, $reportDescription, $reportDate);
    $stmt->execute();

    // Close database connection
    $conn->close();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report</title>
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
        <h2>Add Report to Project</h2>
        <form action="add_report.php" method="post">
            <label for="ReportTitle">Report Title:</label><br>
            <input type="text" id="ReportTitle" name="ReportTitle" required><br>

            <label for="ReportDescription">Report Description:</label><br>
            <textarea id="ReportDescription" name="ReportDescription" rows="4" required></textarea><br>

            <button type="submit" name="projectID" value="<?php echo $projectID; ?>">Submit Report</button>
        </form>

        <!-- Button to return to project details page -->
        <div class="button-container">
            <form action="project_details.php" method="POST">
                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                <button type="submit">Return to Project Details</button>
            </form>
        </div>
    </div>
</body>
</html>
