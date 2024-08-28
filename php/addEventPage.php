<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/addcustomer.css">
    <link rel="stylesheet" href="../css/page3.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/addEvent.css">
    <title>AddEvent</title>
</head>

<body>

    <div class="container">
        <div class="image-container">
            <img src="../Img/fitness-challenge-and-competitions.png" alt="Fitness Challenge and Competitions">
        </div>

        <div class="form-container">
            <h1>Adding Event</h1>
            <form action="addEventPage.php" method="POST" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Event Name:</td>
                        <td><input type="text" name="event_name" value=""></td>
                    </tr>
                    <tr>
                        <td>Venue (Location):</td>
                        <td><input type="text" name="location" value=""></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td><input type="date" name="event_date"></td>
                    </tr>
                    <tr>
                        <td>Time:</td>
                        <td><input type="time" name="time"></td>
                    </tr>
                    <tr>
                        <td>Event Host:</td>
                        <td><input type="text" name="event_host"></td>
                    </tr>
                    <tr>
                        <td>Fee:</td>
                        <td><input type="text" name="fee"></td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td><textarea name="description" rows="4" cols="50"></textarea></td>
                    </tr>
                    <tr>
                        <td>Precautions (Note):</td>
                        <td><input type="text" name="note"></td>
                    </tr>
                    <tr>
                        <td>Banner Image:</td>
                        <td><input type="file" name="banner_image" accept="image/*"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Submit" name="submit">
                            <input type="reset" onclick="window.location='addEventPage.php'" value="Reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php
    $error = array();
    $event_name_class = $location_class = $date_class = $time_class = $fee_class = $event_host_class = $note_class = "";

    if (isset($_POST["submit"])) {
        if (empty($_POST["event_name"])) {
            $error[] = "Event name is empty";
            $event_name_class = "error-field";
        }
        if (empty($_POST["location"])) {
            $error[] = "Location is empty";
            $location_class = "error-field";
        }
        if (empty($_POST["event_date"])) {
            $error[] = "Date is empty";
            $date_class = "error-field";
        }
        if (empty($_POST["time"])) {
            $error[] = "Time is empty";
            $time_class = "error-field";
        }
        if (empty($_POST["fee"])) {
            $error[] = "Fee is empty";
            $fee_class = "error-field";
        } else if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $_POST["fee"])) {
            $error[] = "Fee must be a valid decimal value";
        }
        if (empty($_POST["event_host"])) {
            $error[] = "Event host is empty";
            $event_host_class = "error-field";
        }
        if (empty($_POST["note"])) {
            $error[] = "Note (precautions) is empty";
            $note_class = "error-field";
        }

        if (count($error) > 0) {
            echo "<ul class='error-messages'>";
            foreach ($error as $value) {
                echo "<li>$value</li>";
            }
            echo "</ul>";
        } else {
            // Database connection
            $servername = "localhost";
            $db_username = "root";
            $db_password = "";
            $dbname = "php-assignment";

            $conn = new mysqli($servername, $db_username, $db_password, $dbname);

            // Check connection to php
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO event (event_name, location, event_date, time, fee, event_host, description, note, banner_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $event_name, $location, $event_date, $time, $fee, $event_host, $description, $note, $banner_image);

            // Set parameters and execute
            $event_name = $_POST['event_name'];
            $location = $_POST['location'];
            $event_date = $_POST['event_date'];
            $time = $_POST['time'];
            $fee = $_POST['fee'];
            $event_host = $_POST['event_host'];
            $description = $_POST['description'];
            $note = $_POST['note'];

            // Handle file upload
            $banner_image = null;
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
                $banner_image = file_get_contents($_FILES['banner_image']['tmp_name']);
            }

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo '<script>alert("Event Create Successfully!!")</script>';
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>
</body>

</html>