<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Done Tasks</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['userID'])) {
        header("Location: login.php");
        exit();
    }

    // Include database 
    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Get user ID 
    $userID = $_SESSION['userID'];

    // Get project ID 
    if (isset ($_SESSION['projectID'])){
      $projectID = $_SESSION['projectID'];
    }

    else{
      $projectID = $_POST['projectID'];
    }
    
    // get tasks 
    $sql_tasks = "SELECT * FROM Tasks 
    INNER JOIN TaskAssignments ON Tasks.TaskID = TaskAssignments.TaskID 
    WHERE TaskAssignments.UserID = ? 
    AND Tasks.Status = 'Done' 
    AND Tasks.ProjectID = ?";

    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("ii", $userID, $projectID);
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();

    if ($result_tasks->num_rows > 0) {
        // Output tasks
        echo "<h2 class='task-header'>Done Tasks</h2>";
        echo "<ul class='task-list'>";
        while ($row = $result_tasks->fetch_assoc()) {
            echo "<li class='task-item'>";
            echo "<div class='task-name'>{$row['TaskName']}</div>";
            echo "<div class='task-description'>{$row['Description']}</div>";
            echo "<div class='due-date'>Due: {$row['DueDate']}</div>";
            
            // Button to undo and mark as To Do again
            echo "<form action='undo_task.php' method='post'>";
            echo "<input type='hidden' name='taskID' value='{$row['TaskID']}'>";
            echo "<input type='hidden' name='projectID' value='{$row['ProjectID']}'>";
            echo "<button type='submit' class='undo-button'>Undo</button>";
            echo "</form>";
            
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='no-tasks-message'>No tasks marked as Done.</p>";
    }

    // Close statement and connection
    $stmt_tasks->close();
    $conn->close();
    ?>
    <br>
    <form action="view_tasks.php" method="POST">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <button type="submit" class="button">Return to View Tasks</button>
    </form>
  </div>
</body>
</html>
