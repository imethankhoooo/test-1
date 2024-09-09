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
    <link rel="stylesheet" href="../css/booking.css">
    
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