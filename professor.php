<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="This is the university database">
        <meta name="keywords" content="">
        <link rel="stylesheet" href="styles.css">
        <title>University</title>
        <style>
            table{
                border-collapse: collapse;
                width: 100%;
                color: #588c7e;
                font-family: monospace;
                font-size: 15px;
                text-align: left;
            }

            th{
                background-color: #588c7e;
                color: white;
            }

            tr:nth-child(even) {background-color: #f2f2f2}

            footer{
                position: fixed;
                bottom: 0
            }
        </style>
    </head>

    <body>
        <?php include "includes/nav.php" ?>
        <?php include "includes/header.php" ?>

        <?php
            // Establish the database connection
            $servername = "mariadb";
            $username = "your_username";  // Add your DB username
            $password = "your_password";  // Add your DB password
            $database = "your_database";  // Add your DB name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
        ?>

        <h1>Professor Class Info</h1>
        <br>

        <form method="POST">
            <label for="pSSN">Enter Professor SSN:</label>
            <input type="text" id="pSSN" name="pSSN" required>
            <button type="submit">Submit</button>
        </form>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Classroom</th>
                    <th>Meeting Days</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    if (isset($_POST['pSSN'])) {
                        $pSSN = $_POST['pSSN'];

                        $sql = "SELECT 
                                    Courses.Title AS CourseTitle,
                                    Sections.Classroom,
                                    Sections.MeetingDays,
                                    Sections.StartTime,
                                    Sections.EndTime
                                FROM 
                                    Sections
                                JOIN 
                                    Courses ON Sections.CourseNumber = Courses.CourseNumber
                                WHERE 
                                    Sections.ProfessorSSN = ?";
                        
                        // Prepare and execute the statement
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $pSSN);  // 's' means the parameter is a string
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['CourseTitle'] . "</td>
                                        <td>" . $row['Classroom'] . "</td>
                                        <td>" . $row['MeetingDays'] . "</td>
                                        <td>" . $row['StartTime'] . "</td>
                                        <td>" . $row['EndTime'] . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No classes found for this professor.</td></tr>";
                        }

                        $stmt->close();
                    }
                ?>
            </tbody>
        </table>

        <h1>Professor Grade Info</h1>
        <br>

        <form method="POST">
            <label for="courseNum">Enter Course Number:</label>
            <input type="text" id="courseNum" name="courseNum" required>
            <label for="sectionNum">Enter Section Number:</label>
            <input type="text" id="sectionNum" name="sectionNum" required>
            <button type="submit">Submit</button>
        </form>
        <br>

        <table>
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Count</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    if (isset($_POST['courseNum']) && isset($_POST['sectionNum'])) {
                        $courseNum = $_POST['courseNum'];
                        $sectionNum = $_POST['sectionNum'];

                        $sql = "SELECT 
                                    Grade,
                                    COUNT(*) AS NumberOfStudents
                                FROM 
                                    Enrollments
                                WHERE 
                                    SectionNumber = ? AND CourseNumber = ?
                                GROUP BY 
                                    Grade";
                        
                        // Prepare and execute the statement
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $sectionNum, $courseNum);  // 'ss' means both parameters are strings
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['Grade'] . "</td>
                                        <td>" . $row['NumberOfStudents'] . "</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No grade data available for this course/section.</td></tr>";
                        }

                        $stmt->close();
                    }
                ?>
            </tbody>
        </table>

        <?php include "includes/footer.php" ?>

        <script src="javascript/script.js"></script>
    </body>
</html>
