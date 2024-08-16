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
            <form action="addEventPage.php" method="POST">
                <table>
                    <tr>
                        <td>Venue:</td>
                        <td><input type="text" name="venue" value=""></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td><input type="date" name="date"></td>
                    </tr>
                    <tr>
                        <td>Time:</td>
                        <td><input type="time" name="time"></td>
                    </tr>
                    <tr>
                        <td>Fee:</td>
                        <td><input type="text" name="fee"></td>
                    </tr>
                    <tr>
                        <td>Event Host:</td>
                        <td><input type="text" name="eventHost"></td>
                    </tr>
                    <tr>
                        <td>Precautions:</td>
                        <td><input type="text" name="precautions"></td>
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
            $venueClass = $dateClass = $timeClass = $feeClass = $eventHostClass = $precautionsClass = "";

            if (isset($_POST["submit"])) {
                if (empty($_POST["venue"])) {
                    $error[] = "Venue is empty";
                    $venueClass = "error-field";
                }
                if (empty($_POST["date"])) {
                    $error[] = "Date is empty";
                    $dateClass = "error-field";
                }
                if (empty($_POST["time"])) {
                    $error[] = "Time is empty";
                    $timeClass = "error-field";
                }
                if (empty($_POST["fee"])) {
                    $error[] = "Fee is empty";
                    $feeClass = "error-field";
                } else if (!preg_match("/^[1-9]$/", $_POST["fee"])) {
                    $error[]="Fee must be digit value";
                }
                    if (empty($_POST["eventHost"])) {
                    $error[] = "Event Host is empty";
                    $eventHostClass = "error-field";
                }
                if (empty($_POST["precautions"])) {
                    $error[] = "Precautions is empty";
                    $precautionsClass = "error-field";
                }
                
                if (count($error) > 0) {
                    echo "<ul class='error-messages'>";
                    foreach ($error as $value) {
                        echo "<li>$value</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "Event Created Successfully!";
                }
            }
            ?>
</body>


</html>