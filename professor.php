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

        <?php include "config.php" ?>

        <h1>Professor Class Info</h1>
        <br>

        <form method="GET">
            <label for="SSN">Enter Professor SSN:</label>
            <input type="text" id="SSN" name="SSN" required>
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
                    if (isset($_GET['SSN'])) {
                        $pSSN = $_GET['SSN'];

                        $sql = "
                                SELECT c.Title, s.Classroom, s.MeetingDays, s.StartTime, s.EndTime
                                FROM Professors p
                                JOIN Sections s ON p.SSN = s.ProfessorID
                                JOIN Courses c ON s.CourseID = c.CourseID
                                WHERE p.SSN = ?";

                        // Prepare and execute the statement
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $pSSN);  // 's' means the parameter is a string
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row['Title'] . "</td>
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
                    $conn->close();
                ?>
            </tbody>
        </table>

        <h1>Professor Grade Distribution</h1>
        <br>

        <form method="GET">
            <label for="CourseID">Enter Course Number:</label>
            <input type="number" id="CourseID" name="CourseID" required>
            <label for="SectionID">Enter Section Number:</label>
            <input type="number" id="SectionID" name="SectionID" required>
            <button type="submit">Submit</button>
        </form>
        <br>

        <table>
            <thead>
                <th>Grade</th>
                <th>Number of Students</th>
            </thead>
            <tbody>
                <?php include "config.php"?>
                <?php
                    if (isset($_GET['CourseID']) && isset($_GET['SectionID'])) {
                        $courseID = intval($_GET['CourseID']);
                        $sectionID = intval($_GET['SectionID']);

                        $sql = "
                                SELECT Grade, COUNT(*) AS grade_count
                                FROM Enrollment e
                                JOIN Sections s ON e.SectionID = s.SectionID
                                WHERE s.CourseID = ? AND s.SectionID = ?
                                GROUP BY Grade
                                ORDER BY FIELD(Grade, 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'F');
                                ";

                        // Prepare and bind
                        if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ii", $courseID, $sectionID);
                        $stmt->execute();


                        // Get the result
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                                echo "<h3>Grade Distribution for Course ID: $courseID, Section: $sectionID</h3>";
                        while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($row['Grade']) . "</td>
                                        <td>" . htmlspecialchars($row['grade_count']) . "</td>
                                    </tr>";
                        }
                            echo "</table>";
                        } else {
                            echo "<p>No students found for this course and section.</p>";
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        echo "<p>Error in the query.</p>";
                    }
                } else {
                    echo "Please enter a valid Course ID and Section ID.";
                }
                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>

        <?php include "includes/footer.php" ?>

        <script src="javascript/script.js"></script>
    </body>
</html>

