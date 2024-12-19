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
            footer{
                position: fixed;
                bottom: 0
            }
        </style>
    </head>

    <body>
        <?php include "includes/nav.php" ?>
        <?php include "includes/header.php" ?>

        <h1>List of Professors</h1>
        <br>
        <table>
                <thread>
                        <tr>
                                <th>SSN</th>
                                <th>Name</th>
                                <th>Street</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>AreaCode</th>
                                <th>Telephone</th>
                                <th>Sex</th>
                                <th>Title</th>
                                <th>Salary</th>
                                <th>Degrees</th>
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
                        $sql = "SELECT * FROM Professors";
                        $result = $conn->query($sql);

                        if (!$result) {
                                die("Invalid query: " . $conn->error);
                        }

                        // read data of each row
                        while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row[SSN] . "</td>
                                        <td>" . $row[Name] . "</td>
                                        <td>" . $row[Street] . "</td>
                                        <td>" . $row[City] . "</td>
                                        <td>" . $row[State] . "</td>
                                        <td>" . $row[Zip] . "</td>
                                        <td>" . $row[AreaCode] . "</td>
                                        <td>" . $row[Telephone] . "</td>
                                        <td>" . $row[Sex] . "</td>
                                        <td>" . $row[Title] . "</td>
                                        <td>" . $row[Salary] . "</td>
                                        <td>" . $row[Degrees] . "</td>

                                </tr>";
                        }
                        ?>
                </tbody>
        </table>
        <?php include "includes/footer.php" ?>


        <script src="javascript/script.js"></script>
    </body>
</html>
