<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}


// Check if projectID is provided in the URL
if (!isset($_POST['projectID'])) {
    echo "Project ID not provided";
    exit();
}


// Database connection
include 'dbconnect.php';
$conn = new mysqli($servername, $username, $password, $dbname);

// Get projectID
$projectID = $_POST['projectID'];


if (isset($projectID)){

        $taskName = $_POST['taskName'];
        $taskDescription = $_POST['description']; 
        $assignedUsers = $_POST['assignedUsers'];
        $dueDate = $_POST['dueDate'];
        
        // Insert task
        $sql = "INSERT INTO Tasks (TaskName, Description, DueDate, ProjectID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $taskName, $taskDescription, $dueDate, $projectID);
        
        if ($stmt->execute()) {
            $taskID = $stmt->insert_id;
            
        // Assign users to the task
        foreach ($assignedUsers as $username) {
          // Fetch UserID 
          $sql_user = "SELECT UserID FROM Users WHERE UserName = ?";
          $stmt_user = $conn->prepare($sql_user);
          $stmt_user->bind_param("s", $username);
          $stmt_user->execute();
          $result_user = $stmt_user->get_result();
          $row_user = $result_user->fetch_assoc();
          $userID = $row_user['UserID'];

          // Insert
          $sql_assignment = "INSERT INTO TaskAssignments (TaskID, UserID, ProjectID) VALUES (?, ?, ?)";
          $stmt_assignment = $conn->prepare($sql_assignment);
          $stmt_assignment->bind_param("iii", $taskID, $userID, $projectID); 
          $stmt_assignment->execute();
        }

            
            echo "Task added successfully!";
        } else {
            echo "Error adding task: " . $stmt->error;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        form {
            display: grid;
            gap: 10px;
            margin-top: 20px;
            text-align: left;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        .assigned-users {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
        }

        .assigned-users input[type="checkbox"] {
            margin-right: 5px;
            vertical-align: middle;
        }

        .button-container {
            margin-top: 20px;
        }

        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #007bff;
            color: #fff;
        }

        .button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-tasks"></i> Add Task</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="taskName"><i class="fas fa-tasks"></i> Task Name:</label>
                <input type="text" id="taskName" name="taskName" required>
            </div>

            <div>
                <label for="description"><i class="fas fa-align-left"></i> Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div>
                <label for="dueDate"><i class="fas fa-calendar-alt"></i> Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" required>
            </div>

            <div>
                <label for="assignedUsers"><i class="fas fa-user"></i> Assigned Users:</label><br>
                <div class="assigned-users">
                    <?php
                        include 'get_members.php';
                        foreach ($usernames as $username) {
                            echo "<input type='checkbox' id='$username' name='assignedUsers[]' value='$username'>";
                            echo "<label for='$username'>$username</label>";
                        }
                    ?>
                </div>
            </div>
            <form action="add_task.php" method="post">        
            <button type="submit" name="projectID" value="<?php echo $projectID; ?>"><i class="fas fa-plus-circle"></i> Add Task</button>
            </form>
        </form>

        <!-- Button to return to project details page -->
        <div class="button-container">
            <form action="project_details.php" method="POST">
                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                <button type="submit" class="button"><i class="fas fa-arrow-left"></i> Return to Project Details</button>
            </form>
        </div>
    </div>
</body>
</html>
