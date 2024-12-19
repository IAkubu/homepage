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

        <h1>List of Departments</h1>
        <br>
        <table>
                <thread>
                        <tr>
                                <th>DeptID</th>
                                <th>Name</th>
                                <th>Telephone</th>
                                <th>OfficeLocation</th>
                                <th>Chairperson</th>
                        </tr>
                </thread>

                <tbody>
                        <?php
                        $servername = "mariadb";
                        $username = "";
                        $password = "";
                        $database = "";

                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $database);

                        if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                        }

                        // read all row from database table
                        $sql = "SELECT * FROM Departments";
                        $result = $conn->query($sql);

                        if (!$result) {
                                die("Invalid query: " . $conn->error);
                        }

                        // read data of each row
                        while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row[DeptID] . "</td>
                                        <td>" . $row[Name] . "</td>
                                        <td>" . $row[Telephone] . "</td>
                                        <td>" . $row[OfficeLocation] . "</td>
                                        <td>" . $row[Chairperson] . "</td>
                                </tr>";
                        }
                        ?>
                </tbody>
        </table>

        <h1>List of Courses</h1>
        <br>
        <table>
                <thread>
                        <tr>
                                <th>CourseID</th>
                                <th>Title</th>
                                <th>Textbook</th>
                                <th>Units</th>
                                <th>DeptID</th>
                                <th>PrerequisiteID</th>
                        </tr>
                </thread>

                <tbody>
                        <?php
                        $servername = "mariadb";
                        $username = "";
                        $password = "";
                        $database = "";

                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $database);

                        if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                        }

                        // read all row from database table
                        $sql = "SELECT * FROM Courses";
                        $result = $conn->query($sql);

                        if (!$result) {
                                die("Invalid query: " . $conn->error);
                        }

                        // read data of each row
                        while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row[CourseID] . "</td>
                                        <td>" . $row[Title] . "</td>
                                        <td>" . $row[Textbook] . "</td>
                                        <td>" . $row[Units] . "</td>
                                        <td>" . $row[DeptID] . "</td>
                                        <td>" . $row[PrerequisiteID] . "</td>
                                </tr>";
                        }
                        ?>
                </tbody>
        </table>
        <?php include "includes/footer.php" ?>


        <script src="javascript/script.js"></script>
    </body>
</html>
