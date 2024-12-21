<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="This the university database">
        <meta name="keywords" content="">
        <link rel="stylesheet" href="styles.css">
        <title>University</title>
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                color: #588c7e;
                font-family: monospace;
                font-size: 15px;
                text-align: left;
            }

            th {
                background-color: #588c7e;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            footer {
                position: fixed;
                bottom: 0;
            }
        </style>
    </head>

    <body>
        <?php include "includes/nav.php" ?>
        <?php include "includes/header.php" ?>

        <h1>Section Info</h1>
        <br>
        <form method="GET">
            <label for="CourseID">Enter Course Number:</label>
            <input type="number" id="CourseID" name="CourseID" required>
            <button type="submit">Submit</button>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Section No.</th>
                    <th>Classroom</th>
                    <th>Meeting Days</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Enrolled Students</th>
                </tr>
            </thead>

            <tbody>
                <?php include "config.php"?>
                <?php
                if (isset($_GET['CourseID'])) {
                    $courseID = $_GET['CourseID'];

                    // Query to get sections and number of students enrolled
                    $sql = "
                        SELECT
                                s.SectionID,
                                s.SectionNumber,
                                s.Classroom,
                                s.MeetingDays,
                                s.StartTime,
                                s.EndTime,
                        COUNT(e.StudentID) AS StudentsEnrolled
                        FROM
                                Sections s
                        JOIN
                                Courses c ON s.CourseID = c.CourseID
                        LEFT JOIN
                                Enrollment e ON s.SectionID = e.SectionID
                        WHERE
                                c.CourseID = ?
                        GROUP BY
                                s.SectionID;
                        ";

                    // Prepare and bind parameters
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $courseID); // "i" denotes an integer parameter

                    // Get the result
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Display the sections for the given course
                    echo "<h2>Sections for Course ID: $courseID</h2>";

                    // Loop through the results and display each section
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . $row['SectionNumber'] . "</td>
                            <td>" . $row['Classroom'] . "</td>
                            <td>" . $row['MeetingDays'] . "</td>
                            <td>" . $row['StartTime'] . "</td>
                            <td>" . $row['EndTime'] . "</td>
                            <td>" . $row['StudentsEnrolled'] . "</td>
                          </tr>";
                    }

                    $stmt->close();
                }
                ?>
            </tbody>
        </table>

        <h1>Student Info</h1>
        <br>
        <form method="GET">
            <label for="StudentID">Enter Student ID:</label>
            <input type="text" id="StudentID" name="StudentID" required>
            <button type="submit">Submit</button>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Professor</th>
                    <th>Grade</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($_GET['StudentID'])) {
                    include "config.php"; // Assuming this file sets up the $conn variable

                    $studentID = $_GET['StudentID'];

                    // Query to get student details along with course and grade
                    $sql = "
                        SELECT
                            c.Title AS course_title,
                            e.Grade AS grade,
                            p.Name AS professor_name
                        FROM
                            Enrollment e
                        JOIN
                            Sections s ON e.SectionID = s.SectionID
                        JOIN
                            Courses c ON s.CourseID = c.CourseID
                        JOIN
                            Professors p ON s.ProfessorID = p.SSN
                        WHERE
                            e.StudentID = ?
                        ORDER BY
                            c.Title;
                        ";



                    // Prepare and execute the statement
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $studentID); // 's' means it's a string
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Display results in a table
                        while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['course_title']) . "</td>
                                <td>" . htmlspecialchars($row['professor_name']) . "</td>
                                <td>" . htmlspecialchars($row['grade']) . "</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No courses found for this student.</td></tr>";
                    }

                    $stmt->close();
                }
                    $conn->close();
                ?>
            </tbody>
        </table>

        <?php include "includes/footer.php" ?>

        <script src="javascript/script.js"></script>
    </body>
</html>
