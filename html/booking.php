<?php
session_start();


$conn = new mysqli('localhost', 'root', '', 'php-assginment');

if ($conn->connect_error) {
    die("connect error: " . $conn->connect_error);
}


$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;


$sql = "SELECT event_name, event_date, start_time, end_time, fee, seat FROM event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

$member_id = $_SESSION['member_id'] ?? null;


$member_info = null;
if ($member_id) {
    $sql = "SELECT name, email, phone FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member_info = $result->fetch_assoc();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $member_info) {
    $num_tickets = $_POST['num_tickets'];


    $sql = "INSERT INTO bookings (event_id, user_id, name, email, phone, num_tickets) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssi", $event_id, $member_id, $member_info['name'], $member_info['email'], $member_info['phone'], $num_tickets);
    
    if ($stmt->execute()) {
        $success_message = "Booking SuccessFul";

        $new_seats = $event['seat'] - $num_tickets;
        $update_sql = "UPDATE event SET seat = ? WHERE event_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_seats, $event_id);
        $update_stmt->execute();
    } else {
        $error_message = "Booking Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event: <?php echo htmlspecialchars($event['event_name']); ?></title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .nav-menu {
            list-style: none;
        }
        .nav-menu li {
            display: inline;
            margin-left: 20px;
        }
        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-menu a:hover {
            color: #ecf0f1;
        }
        main {
            padding-top: 100px;
            min-height: calc(100vh - 150px);
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .booking-section {
            max-width: 600px;
            width: 100%;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .booking-form h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        .event-details {
            background-color: #ecf0f1;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .event-details p {
            margin: 0.5rem 0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        .submit-btn:hover {
            background-color: #2980b9;
        }
        .submit-btn:active {
            transform: translateY(1px);
        }
        .message {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 6px;
            font-weight: 500;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
            margin-top: 1rem;
            color: #3498db;
        }
        .footer {
            background-color: #34495e;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            .booking-section {
                margin: 1rem;
                padding: 1.5rem;
            }
            .navbar {
                flex-direction: column;
                padding: 1rem;
            }
            .nav-menu {
                margin-top: 1rem;
            }
            .nav-menu li {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="home.php" class="logo">First Fitness</a>
            <ul class="nav-menu">
                <li><a href="event.php?event_id=<?php echo $event_id; ?>">Back to Event</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="booking-section">
            <div class="booking-form">
                <h2>Book Event: <?php echo htmlspecialchars($event['event_name']); ?></h2>
                
                <?php
                if (isset($success_message)) {
                    echo "<div class='message success'>" . htmlspecialchars($success_message) . "</div>";
                }
                if (isset($error_message)) {
                    echo "<div class='message error'>" . htmlspecialchars($error_message) . "</div>";
                }
                ?>

                <div class="event-details">
                    <p><strong>Event Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                    <p><strong>Time:</strong> <?php echo htmlspecialchars($event['start_time']) . " - " . htmlspecialchars($event['end_time']); ?></p>
                    <p><strong>Price per Ticket:</strong> $<?php echo htmlspecialchars($event['fee']); ?></p>
                    <p><strong>Available Seats:</strong> <?php echo htmlspecialchars($event['seat']); ?></p>
                </div>

                <?php if ($member_info): ?>
                    <form method="post" action="payment.php?event_id=<?php echo $event_id; ?>" id="bookingForm">
                        <div class="form-group">
                            <label for="num_tickets">Number of Tickets:</label>
                            <input type="number" id="num_tickets" name="num_tickets" min="0" max="<?php echo $event['seat']; ?>" required>
                        </div>
                        <div class="total-price">
                            Total Price: $<span id="totalPrice">0</span>
                        </div>
                        <button type="submit" class="submit-btn">Book Now</button>
                    </form>
                <?php else: ?>
                    <p class="message error">Please log in to book tickets.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 EventMaster. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numTicketsInput = document.getElementById('num_tickets');
            const totalPriceSpan = document.getElementById('totalPrice');
            const ticketPrice = <?php echo $event['fee']; ?>;

            function updateTotalPrice() {
                const numTickets = parseInt(numTicketsInput.value) || 0;
                const totalPrice = numTickets * ticketPrice;
                totalPriceSpan.textContent = totalPrice.toFixed(2);
            }

            if (numTicketsInput) {
                numTicketsInput.addEventListener('input', updateTotalPrice);
                updateTotalPrice(); // Initial calculation
            }
        });
    </script>
</body>
</html>