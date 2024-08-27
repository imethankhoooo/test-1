<?php
// Start session to manage user login
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $event = $_POST['event'];

    // Here you would normally add code to save this data to a database

    // Simulate successful submission
    $_SESSION['success_message'] = "You have successfully joined the event: $event!";
    header('Location: join_event.php'); // Redirect to avoid form resubmission
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Event</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS file -->
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"],
        input[type="email"],
        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .success-message {
            color: green;
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Join an Event</h1>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="success-message">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Remove the message after displaying
                ?>
            </div>
        <?php endif; ?>

        <form id="joinEventForm" action="join_event.php" method="POST" onsubmit="return validateForm()">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="event">Select Event:</label>
            <select id="event" name="event" required>
                <option value="">--Select an Event--</option>
                <option value="Yoga Class">Yoga Class</option>
                <option value="Swimming Competition">Swimming Competition</option>
                <option value="Zumba Session">Zumba Session</option>
                <option value="Weightlifting Workshop">Weightlifting Workshop</option>
            </select>

            <input type="submit" value="Join Event">
        </form>
    </div>

    <script>
        function validateForm() {
            // Simple form validation
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var event = document.getElementById('event').value;

            if (name == "" || email == "" || event == "") {
                alert("Please fill in all fields.");
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
