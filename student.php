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
                        s.Classroom, 
                        s.MeetingDays, 
                        s.StartTime, 
                        s.EndTime, 
                        COUNT(e.StudentID) AS EnrolledStudents
                    FROM Sections s
                    LEFT JOIN Enrollment e ON s.SectionID = e.SectionID
                    WHERE s.CourseID = :courseID
                    GROUP BY s.SectionID, s.Classroom, s.MeetingDays, s.StartTime, s.EndTime
                    ORDER BY s.SectionNumber
                    ";

                    // Prepare and execute the query
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['courseID' => $courseID]);

                     // Fetch and display results       
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['SectionID']}</td>
                                <td>{$row['Classroom']}</td>
                                <td>{$row['MeetingDays']}</td>
                                <td>{$row['StartTime']}</td>
                                <td>{$row['EndTime']}</td>
                                <td>{$row['EnrolledStudents']}</td>
                              </tr>";
                    }

                    $stmt->close();
                } else {
                    echo "<p>Please provide a course ID to view sections.</p>";
                }
                ?>
            </tbody>
        </table>

        <h1>Student Info</h1>
        <br>
        <form method="GET">
            <label for="studentID">Enter Student ID:</label>
            <input type="number" id="studentID" name="studentID" required>
            <button type="submit">Submit</button>
        </form>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Courses</th>
                    <th>Grades</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                if (isset($_GET['studentID'])) {
                    include "config.php"; // Assuming this file sets up the $conn variable

                    $studentID = $_GET['studentID'];

                    $sql = "SELECT 
                                Courses.Title AS CourseTitle,
                                Enrollments.Grade
                            FROM 
                                Enrollments
                            JOIN 
                                Sections ON Enrollments.SectionNumber = Sections.SectionNumber
                            JOIN 
                                Courses ON Sections.CourseIDber = Courses.CourseIDber
                            WHERE 
                                Enrollments.CampusWideID = ?";

                    // Prepare and execute the statement
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $studentID); // 's' means it's a string
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['CourseTitle'] . "</td>
                                    <td>" . $row['Grade'] . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No courses found for this student.</td></tr>";
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
