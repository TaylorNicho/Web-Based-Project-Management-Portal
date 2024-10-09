<?php
// Start session and include database connection
session_start();
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);


// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check userID 
if (!isset($_POST['userID'])) {
    // Redirect if userID is not provided
    header("Location: view_members.php");
    exit();
}

// Fetch userID from POST data
$userID = $_POST['userID'];

// SQL to delete the user from ProjectUsers table
$sql_delete_user = "DELETE FROM ProjectUsers WHERE UserID = ?";
$stmt_delete_user = $conn->prepare($sql_delete_user);
$stmt_delete_user->bind_param("i", $userID);
$stmt_delete_user->execute();

// SQL to delete the user from Users table
$sql_delete_user = "DELETE FROM Users WHERE UserID = ?";
$stmt_delete_user = $conn->prepare($sql_delete_user);
$stmt_delete_user->bind_param("i", $userID);
$stmt_delete_user->execute();

// Redirect to view_members.php after deleting the user
header("Location: view_members.php");
exit();
?>
