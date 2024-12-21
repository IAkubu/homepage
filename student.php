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
        <form method="POST">
            <label for="courseNum">Enter Course Number:</label>
            <input type="text" id="courseNum" name="courseNum" required>
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
                <?php 
                if (isset($_POST['courseNum'])) {
                    include "config.php"; // Assuming this file sets up the $conn variable

                    $courseNum = $_POST['courseNum'];

                    $sql = "SELECT 
                                Sections.SectionNumber,
                                Sections.Classroom,
                                Sections.MeetingDays,
                                Sections.StartTime,
                                Sections.EndTime,
                                COUNT(Enrollments.EnrollmentID) AS NumberOfStudents
                            FROM 
                                Sections
                            LEFT JOIN 
                                Enrollments ON Sections.SectionNumber = Enrollments.SectionNumber
                            WHERE 
                                Sections.CourseNumber = ?
                            GROUP BY 
                                Sections.SectionNumber, Sections.Classroom, Sections.MeetingDays, Sections.StartTime, Sections.EndTime";

                    // Prepare and execute the statement
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $courseNum); // 's' means it's a string
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['SectionNumber'] . "</td>
                                    <td>" . $row['Classroom'] . "</td>
                                    <td>" . $row['MeetingDays'] . "</td>
                                    <td>" . $row['StartTime'] . "</td>
                                    <td>" . $row['EndTime'] . "</td>
                                    <td>" . $row['NumberOfStudents'] . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No sections found for this course.</td></tr>";
                    }

                    $stmt->close();
                }
                ?>
            </tbody>
        </table>

        <h1>Student Info</h1>
        <br>
        <form method="POST">
            <label for="studentID">Enter Student ID:</label>
            <input type="text" id="studentID" name="studentID" required>
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
                if (isset($_POST['studentID'])) {
                    include "config.php"; // Assuming this file sets up the $conn variable

                    $studentID = $_POST['studentID'];

                    $sql = "SELECT 
                                Courses.Title AS CourseTitle,
                                Enrollments.Grade
                            FROM 
                                Enrollments
                            JOIN 
                                Sections ON Enrollments.SectionNumber = Sections.SectionNumber
                            JOIN 
                                Courses ON Sections.CourseNumber = Courses.CourseNumber
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
