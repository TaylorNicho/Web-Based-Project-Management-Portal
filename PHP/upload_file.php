<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Database connection 
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Create uploads directory if it doesn't exist
$uploadsDirectory = 'uploads';
if (!file_exists($uploadsDirectory)) {
    mkdir($uploadsDirectory, 0755, true);
}

    
if (isset($_POST['projectID'])) { 
    $projectID = $_POST['projectID']; 
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // database connection
    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Get project ID
    $projectID = $_POST['projectID'];

    // File upload directory
    $targetDir = "uploads/";

    // Get file info
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $description = $_POST['description'];

    // Check if file is selected
    if (!empty($_FILES["file"]["name"])) {
        // Allow certain file formats
        $allowTypes = array('pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileType, $allowTypes)) {
            // Upload file 
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {

                // Insert file details 
                $sql = "INSERT INTO Files (FileName, Description, FilePath, UserID, ProjectID) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $fileName, $description, $targetFilePath, $_SESSION['userID'], $projectID);

                if (!$stmt) {
                    die("Error preparing statement: " . $conn->error);
                }

                if ($stmt->execute()) {
                    echo "File uploaded successfully!";
                    header("loaction: view_files.php");
                } else {
                    echo "Error uploading file: " . $stmt->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF files are allowed.";
        }
    } else {
        echo "Please select a file to upload.";
    }

    // Close database connection
    $conn->close();
}
?>
