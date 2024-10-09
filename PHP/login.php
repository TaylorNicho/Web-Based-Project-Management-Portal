<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check username and password
    if (isset($_POST['username']) && isset($_POST['password'])) {

        // Database connection 
        include 'dbconnect.php';

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL statement to check username and password
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT UserID FROM Users WHERE Username='$username' AND Password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Authentication successful
            $row = $result->fetch_assoc();
            $_SESSION['userID'] = $row['UserID']; 
            $_SESSION['username'] = $username; 
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // failed
            header("Location: login.html?error=1");
            exit();
        }
    } else {
        // username or password is not provided
        header("Location: login.html");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
?>
