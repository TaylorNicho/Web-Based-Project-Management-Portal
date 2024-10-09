<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Tasks</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .button-container {
      display: flex;
      justify-content: space-between;
    }
  </style>
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

    // database connection
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
    
    // get tasks assigned to the user
    $sql_tasks = "SELECT * FROM Tasks 
    INNER JOIN TaskAssignments ON Tasks.TaskID = TaskAssignments.TaskID 
    WHERE TaskAssignments.UserID = ? 
    AND Tasks.Status = 'To Do' 
    AND Tasks.ProjectID = ?";
    
    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("ii", $userID, $projectID);
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();

    if ($result_tasks->num_rows > 0) {
        // Output tasks
        echo "<h2 class='task-header'>Tasks Assigned to You</h2>";
        echo "<ul class='task-list'>";
        while ($row = $result_tasks->fetch_assoc()) {
            echo "<li class='task-item'>";
            echo "<div class='task-name'>{$row['TaskName']}</div>";
            echo "<div class='task-description'>{$row['Description']}</div>";
            echo "<div class='due-date'>Due: {$row['DueDate']}</div>";

            // Add button to mark task as done
            echo "<form action='mark_task_done.php' method='post'>";
            echo "<input type='hidden' name='taskID' value='{$row['TaskID']}'>";
            echo "<input type='hidden' name='projectID' value='{$row['ProjectID']}'>";
            echo "<button type='submit' class='mark-done-button'>Mark as Done</button>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='no-tasks-message'>No tasks assigned to you.</p>";
    }

    // Close connection
    $stmt_tasks->close();
    $conn->close();
    ?>
    <br>
    <div class="button-container">
        <!-- Button to view all tasks marked as Done -->
        <form action="view_done_tasks.php" method="POST">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <button type="submit" class="button">View Completed Tasks</button>
        </form>
        <!-- Button to return to project details page -->
        <form action="project_details.php" method="POST">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <button type="submit" class="button">Return to Project Details</button>
        </form>
    </div>
  </div>
</body>
</html>
