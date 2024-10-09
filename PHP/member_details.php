<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Details</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .container {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      font-family: Arial, sans-serif;
    }

    .section {
      margin-bottom: 20px;
    }

    .button-container {
      margin-top: 20px;
    }

    .button {
      display: inline-block;
      padding: 10px 20px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .delete-button {
      background-color: #dc3545;
      color: #fff;
    }

    .button i {
      margin-right: 5px;
    }

    .task-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 10px;
      text-align: left;
    }

    .task-item {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      color: #000; 
    }

    .task-item .title {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .task-item .description {
      color: #000; 
    }

    .task-item .due-date {
      color: #000; 
    }

    .note-list {
      text-align: left;
    }

    .note-list li {
      margin-bottom: 5px;
    }

    .user-details-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 10px;
      text-align: left;
    }

    .user-details-grid div {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
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

    include 'dbconnect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check project ID
    if (!isset($_POST['projectID'])) {
        echo "Project ID not provided.";
        exit();
    }

    $projectID = $_POST['projectID'];

    // Check userID 
    if (isset($_POST['userID'])) {
        $userID = $_POST['userID'];

        // get user details
        $sql_user = "SELECT * FROM Users WHERE UserID = ?";
        $stmt_user = $conn->prepare($sql_user);

        if ($stmt_user) {
            $stmt_user->bind_param("i", $userID);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();

            if ($result_user->num_rows > 0) {
                $user_row = $result_user->fetch_assoc();
                $username = $user_row['UserName'];
                $email = $user_row['Email'];
                $notes = $user_row['Notes'];

                // user details
                echo "<div class='section'>";
                echo "<h2>User Details</h2>";
                echo "<div class='user-details-grid'>";
                echo "<div>Username: $username</div>";
                echo "<div>Email: $email</div>";
                echo "</div>";
                echo "</div>";

                // Delete button
                echo "<div class='section'>";
                echo "<div class='button-container'>";
                echo "<form action='delete_member.php' method='post'>";
                echo "<input type='hidden' name='userID' value='$userID'>";
                echo "<button type='submit' class='button delete-button'><i class='fas fa-trash-alt'></i> Delete Member</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";

                // Display tasks
                echo "<div class='section'>";
                echo "<h2>Assigned Tasks</h2>";

                // Fetch tasks with status To Do
                $sql_tasks = "SELECT * FROM Tasks 
                              INNER JOIN TaskAssignments ON Tasks.TaskID = TaskAssignments.TaskID 
                              WHERE TaskAssignments.UserID = ? 
                              AND Tasks.Status = 'To Do' 
                              AND Tasks.ProjectID = ?";

                $stmt_tasks = $conn->prepare($sql_tasks);
                $stmt_tasks->bind_param("ii", $userID, $projectID);
                $stmt_tasks->execute();
                $result_tasks = $stmt_tasks->get_result();

                // Check if tasks are found
                if ($result_tasks->num_rows > 0) {
                    echo "<div class='task-grid'>";
                    while ($row_tasks = $result_tasks->fetch_assoc()) {
                        echo "<div class='task-item' style='background-color: #ffc107;'>";
                        echo "<div class='title'>" . $row_tasks['TaskName'] . "</div>";
                        echo "<div class='description'>" . $row_tasks['Description'] . "</div>";
                        echo "<div class='due-date'>Due Date: " . $row_tasks['DueDate'] . "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "No Assigned Tasks Found.";
                }
                echo "</div>";

                // display completed tasks
                echo "<div class='section'>";
                echo "<h2>Completed Tasks</h2>";

                $sql_tasks = "SELECT * FROM Tasks 
                              INNER JOIN TaskAssignments ON Tasks.TaskID = TaskAssignments.TaskID 
                              WHERE TaskAssignments.UserID = ? 
                              AND Tasks.Status = 'Done' 
                              AND Tasks.ProjectID = ?";
                              
                $stmt_tasks = $conn->prepare($sql_tasks);
                $stmt_tasks->bind_param("ii", $userID, $projectID);
                $stmt_tasks->execute();
                $result_tasks = $stmt_tasks->get_result();

                // Check if tasks are found
                if ($result_tasks->num_rows > 0) {
                    echo "<div class='task-grid'>";
                    while ($row_tasks = $result_tasks->fetch_assoc()) {
                        echo "<div class='task-item' style='background-color: #28a745;'>";
                        echo "<div class='title'>" . $row_tasks['TaskName'] . "</div>";
                        echo "<div class='description'>" . $row_tasks['Description'] . "</div>";
                        echo "<div class='due-date'>Due Date: " . $row_tasks['DueDate'] . "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "No Completed Tasks Found.";
                }
                echo "</div>";
    
                echo "<div class='section'>";
                echo "<h2>User Notes</h2>";

                // get user notes based on UserID and ProjectID
                $sql_notes = "SELECT * FROM ProjectNotes WHERE UserID = ? AND ProjectID = ?";
                $stmt_notes = $conn->prepare($sql_notes);
                $stmt_notes->bind_param("ii", $userID, $projectID); 
                $stmt_notes->execute();
                $result_notes = $stmt_notes->get_result();


                // Check if user has notes
                if ($result_notes->num_rows > 0) {
                    echo "<ul class='note-list'>";
                    while ($row_notes = $result_notes->fetch_assoc()) {
                        echo "<li>" . $row_notes['NoteContent'] . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No notes found for this user.";
                }
                echo "</div>";

            } else {
                echo "<p>User not found.</p>";
            }
            $stmt_user->close();
        }
    }
    
    // Close database connection
    $conn->close();
    ?>

    <!-- Button to return to view members page -->
    <div class="section">
      <div class="button-container">
        <form action="view_members.php" method="POST">
            <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
            <button type="submit" class="button"><i class="fas fa-arrow-left"></i> Back to View Members</button>
        </form>
      </div>
    </div>

  </div>
</body>
</html>
