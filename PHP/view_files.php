<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check project ID 
if (!isset($_POST['projectID'])) {
    // Redirect the user to a page where they can select a project
    header("Location: select_project.php");
    exit();
}

// Get the project ID 
$projectID = $_POST['projectID'];

// Database connection 
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get project details 
$sql_project = "SELECT ProjectName FROM Projects WHERE ProjectID = ?";
$stmt_project = $conn->prepare($sql_project);
$stmt_project->bind_param("i", $projectID);
$stmt_project->execute();
$result_project = $stmt_project->get_result();

// Check if project details are found
if ($result_project->num_rows > 0) {
    $row_project = $result_project->fetch_assoc();
    $projectName = $row_project['ProjectName'];
} else {
    $projectName = "Unknown Project";
}

// Initialise filter
$filterUserName = isset($_GET['filterUserName']) ? $_GET['filterUserName'] : "";
$filterFileName = isset($_GET['filterFileName']) ? $_GET['filterFileName'] : "";

// Fetch shared files based on projectID 
$sql_files = "SELECT Files.FileName, Files.Description, Users.UserName, Files.UploadDate
              FROM Files
              INNER JOIN Users ON Files.UserID = Users.UserID
              WHERE Files.ProjectID = ?";
if (!empty($filterUserName)) {
    $sql_files .= " AND Users.UserName LIKE '%$filterUserName%'";
}
if (!empty($filterFileName)) {
    $sql_files .= " AND Files.FileName LIKE '%$filterFileName%'";
}
$sql_files .= " ORDER BY Files.UploadDate DESC";
$stmt_files = $conn->prepare($sql_files);
$stmt_files->bind_param("i", $projectID);
$stmt_files->execute();
$result_files = $stmt_files->get_result();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Files - <?php echo $projectName; ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-danger {
            background-color: #dc3545;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-folder-open"></i> Shared Files - <?php echo $projectName; ?></h2>

        <h3><i class="fas fa-cloud-upload-alt"></i> Upload File</h3>
        <form action="upload_file.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <label for="file"><i class="fas fa-file"></i> Select file to upload:</label>
            <input type="file" name="file" id="file" required>
            <br>
            <label for="description"><i class="fas fa-comment"></i> Description:</label>
            <textarea id="description" name="description"></textarea>
            <br>
            <button type="submit" class="btn btn-success" name="submit"><i class="fas fa-upload"></i> Upload</button>
        </form>

        <hr> 
        
        <!-- Filter options -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <label for="filterUserName"><i class="fas fa-user"></i> Filter by User Name:</label>
            <input type="text" id="filterUserName" name="filterUserName" value="<?php echo $filterUserName; ?>">
            <label for="filterFileName"><i class="fas fa-file"></i> Filter by File Name:</label>
            <input type="text" id="filterFileName" name="filterFileName" value="<?php echo $filterFileName; ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Apply Filters</button>
        </form>
        
        <!-- Table to display shared files -->
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-file"></i> File Name</th>
                    <th><i class="fas fa-info-circle"></i> Description</th>
                    <th><i class="fas fa-user"></i> Uploaded By</th>
                    <th><i class="fas fa-calendar-alt"></i> Upload Date</th>
                    <th><i class="fas fa-download"></i> Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_files->num_rows > 0) {
                    while ($row_files = $result_files->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_files["FileName"] . "</td>";
                        echo "<td>" . $row_files["Description"] . "</td>";
                        echo "<td>" . $row_files["UserName"] . "</td>";
                        echo "<td>" . $row_files["UploadDate"] . "</td>";
                        echo "<td><a href='download.php?file=" . urlencode($row_files["FileName"]) . "' class='btn btn-primary'><i class='fas fa-download'></i> Download</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No shared files found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <form action="project_details.php" method="POST">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Return to Project Details</button>
        </form>
    </div>
</body>
</html>

