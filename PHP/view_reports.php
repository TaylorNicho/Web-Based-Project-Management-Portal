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

// Database connection 
include 'dbconnect.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// get all reports for the current project
$sql = "SELECT * FROM ProjectReports WHERE ProjectID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $projectID);
$stmt->execute();
$result = $stmt->get_result();

// Close database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
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
        <h2>Project Reports</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Report Title</th>
                    <th>Report Description</th>
                    <th>Report Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['ReportTitle']; ?></td>
                        <td><?php echo $row['ReportDescription']; ?></td>
                        <td><?php echo $row['ReportDate']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No reports found for this project.</p>
        <?php endif; ?>


        <div class="button-container">
            <form action="project_details.php" method="POST">
                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                <button type="submit">Return to Project Details</button>
            </form>
        </div>
    </div>
</body>
</html>
