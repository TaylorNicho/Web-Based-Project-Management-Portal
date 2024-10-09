<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        .details-container {
            margin-bottom: 20px;
        }

        .details-container p {
            margin: 5px 0;
        }
        

        .row1,
        .row2 {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .button-container {
            display: flex;
            flex-direction: column; 
        }

        .button {
            padding: 15px 30px; 
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            text-align: center;
            transition: background-color 0.3s ease;
            width: 100%; 
        }

        .add-button {
            background-color: #28a745; 
            color: #fff;
        }

        .view-button {
            background-color: #007bff;
            color: #fff;
        }

        .button i {
            margin-right: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-info-circle"></i> Project Details</h2>
        <div class="details-container">
            <?php
            session_start();

            // Check if user is logged in
            if (!isset($_SESSION['userID'])) {
                header("Location: login.php");
                exit();
            }

            include 'dbconnect.php';
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_POST['projectID'])) { 
                $projectID = $_POST['projectID']; 

                // SQL statement project details
                $sql = "SELECT * FROM Projects WHERE ProjectID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $projectID);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    // Output project details
                    $row = $result->fetch_assoc();
                    echo "<p><strong>Project Name:</strong> " . $row['ProjectName'] . "</p>";
                    echo "<p><strong>Project Description:</strong> " . $row['ProjectDescription'] . "</p>";
                    echo "<p><strong>Start Date:</strong> " . $row['StartDate'] . "</p>";
                    echo "<p><strong>End Date:</strong> " . $row['EndDate'] . "</p>";

                    // Buttons
                    //row1
                    echo "<div class='button-container'>";
                    echo "<div class='row1'>";
                  

                    echo '<form action="add_user_to_project.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-user-plus"></i> Add Members</button>';
                    echo '</form>';
                    

                

                    echo '<form action="view_members.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-users"></i> View Members</button>';
                    echo '</form>';
                    
                

      
                    echo '<form action="add_report.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-file-alt"></i> Add Report</button>';
                    echo '</form>';


                   

                    echo '<form action="view_reports.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-file-alt"></i> View Report</button>';
                    echo '</form>';
                    echo "</div>";


                    //row2

                    echo "<div class='row2'>";

                  

                    echo '<form action="add_task.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-tasks"></i> Add Task</button>';
                    echo '</form>';


                  
                    echo '<form action="view_tasks.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-tasks"></i> View Task</button>';
                    echo '</form>';

             

                    echo '<form action="view_files.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-folder-open"></i> View Shared Files</button>';
                    echo '</form>';


                    echo '<form action="view_projects.php" method="POST">';
                    echo '<button type="submit" name="projectID" class="button add-button" value="' . $row["ProjectID"] . '"><i class="fas fa-arrow-left"></i> Back To Projects</button>';
                    echo '</form>';

                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<p>No project details found</p>";
                }
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
