<?php
// Check if file parameter is set
if (isset($_GET['file']) && !empty($_GET['file'])) {


    // Get the file name 
    $fileName = $_GET['file'];
    
    // Set the path 
    $filePath = 'uploads/' . $fileName;

    // Check if the file exists
    if (file_exists($filePath)) {
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));

        // Read the file and output it to the browser
        readfile($filePath);
        exit;
    } else {
        
        echo "File not found.";
    }
} else {
    // File parameter not set
    echo "Invalid file.";
}
?>
