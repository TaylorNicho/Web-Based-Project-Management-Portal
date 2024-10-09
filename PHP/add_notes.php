<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noteContent = $_POST['noteContent'];
    $userID = $_POST['userID'];
    $projectID = $_POST['projectID']; 

    //database connection
    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL statement
    $sql = "INSERT INTO ProjectNotes (ProjectID, UserID, NoteContent) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $bindResult = $stmt->bind_param("iis", $projectID, $userID, $noteContent);
    if ($bindResult === false) {
        die("Error binding parameters: " . $stmt->error);
    }
    
    if ($stmt->execute()) {
        echo "Note added successfully";
    } else {
        echo "Error adding note: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<link rel="stylesheet" href="styles.css">
<form action="add_notes.php" method="post">
  <input type="hidden" name="userID" value="<?php echo isset($_POST['userID']) ? $_POST['userID'] : ''; ?>">
  <label for="noteContent">Note Content:</label><br>
  <textarea id="noteContent" name="noteContent" required></textarea><br>

  <form action="add_notes.php" method="POST">
        <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
        <button type="submit">Add Note</button>
    </form>
</form>

<div class="button-container">
            <form action="view_members.php" method="POST">
                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                <button type="submit" class="button"><i class="fas fa-arrow-left"></i> Return to View Members </button>
            </form>
        </div>
