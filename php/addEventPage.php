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
                            <input type="submit" value="Submit">
                            <input type="reset" value="Reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php?>
</body>


</html>