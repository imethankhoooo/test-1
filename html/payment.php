<?php
session_start();


$conn = new mysqli('localhost', 'root', '', 'php-assignment');


if ($conn->connect_error) {
    die("Conect Error: " . $conn->connect_error);
}


$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

$member_id = $_SESSION['member_id'] ?? null;


$sql = "SELECT event_name, fee, event_date , seat FROM event WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

$num_tickets = 0;
$total_price = 0;
$ticket_ids = []; 



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_tickets'])) {

    $num_tickets = (int)$_POST['num_tickets'];
    $total_price = $event['fee'] * $num_tickets;
    $currentSeat = $event['seat'];


    
 if(isset($_POST['card_number'])){
    $conn->begin_transaction();
    try {

        $sql = "INSERT INTO bookingInformation (event_id, member_id, paymentAmount, booking_date) VALUES (?, ?,?, CURRENT_TIMESTAMP)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $event_id, $member_id, $total_price);
        $stmt->execute();


        $booking_id = $conn->insert_id;

        for ($i = 0; $i < $num_tickets; $i++) {
            $sql = "INSERT INTO ticket (booking_id, event_id, member_id, issue_date) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $booking_id, $event_id, $member_id);
            $stmt->execute();
            

            $ticket_ids[] = $conn->insert_id;
        }
        $success_message = "Payment successful! Your booking ID is:" . $booking_id;
        $currentSeat -= $num_tickets;
            $update_seat_sql = "UPDATE event SET seat = ? WHERE event_id = ?";
            $update_seat = $conn->prepare($update_seat_sql);
            $update_seat->bind_param("si", $currentSeat, $event_id);
            $update_seat->execute();
            $update_seat->close();
            $conn->commit();
        
    } catch (Exception $e) {

        $conn->rollback();
        $error_message = "Booking failed, please try again. Error:" . $e->getMessage();
        error_log("Booking error: " . $e->getMessage());
    }

  
    if ($conn->error) {
        error_log("Database error after transaction: " . $conn->error);
        if (!isset($error_message)) {
            $error_message = "An error occurred during booking, please try again.";
        }
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for <?php echo htmlspecialchars($event['event_name']); ?></title>
    <link rel="stylesheet" href="../css/payment.css">
    <link rel="stylesheet" href="../css/color.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="home.php" class="logo">First Fitness</a>
        </nav>
    </header>

    <main>
        <?php if (!isset($success_message)): ?>
        <section class="payment-section">
            <div class="payment-form">
                <h2>Payment for <?php echo htmlspecialchars($event['event_name']); ?></h2>
                
                <?php
                if (isset($error_message)) {
                    echo "<div class='message error'>" . htmlspecialchars($error_message) . "</div>";
                }
                ?>

                <div class="payment-details">
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($event['event_name']); ?></p>
                    <p><strong>Price per Ticket:</strong> $<?php echo htmlspecialchars($event['fee']); ?></p>
                </div>

                <form method="post" action="payment.php?event_id=<?php echo "$event_id "?>">
                    <div class="form-group">
                        <label for="num_tickets">Number of Tickets:</label>
                        <input type="number" id="num_tickets" name="num_tickets" required min="1" value="<?php echo htmlspecialchars(($num_tickets))?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number:</label>
                        <input type="text" id="card_number" name="card_number" required placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date:</label>
                        <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" required placeholder="123">
                    </div>
                    <input type="hidden" id="num_tickets" name="num_tickets" required min="1" value="<?php echo htmlspecialchars(($num_tickets))?>">
                    <button type="submit" class="submit-btn">Pay Now</button>
                </form>
            </div>
        </section>
        <?php else: ?>
        <section class="ticket-section">
            <h2>Your Tickets</h2>
            <?php echo "<div class='message success'>" . htmlspecialchars($success_message) . "</div>"; ?>
            <div class="ticket-container">
                <?php foreach ($ticket_ids as $index => $ticket_id): ?>
                    <div class="ticket">
                        <div class="ticket-left">
                            <h3 class="ticket-event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p class="ticket-info"><strong>Ticket ID:</strong> <?php echo $ticket_id; ?></p>
                            <p class="ticket-info"><strong>Event Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                        </div>
                        <div class="ticket-divider"></div>
                        <div class="ticket-right">
                            <p class="ticket-number">Ticket #<?php echo $index + 1; ?></p>
                            <p class="ticket-price">$<?php echo htmlspecialchars($event['fee']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>&copy; 2024 EventMaster. All rights reserved.</p>
    </footer>

    <script>
        document.querySelector('form')?.addEventListener('submit', function(e) {
            var numTickets = document.getElementById('num_tickets').value;
            var cardNumber = document.getElementById('card_number').value;
            var expiryDate = document.getElementById('expiry_date').value;
            var cvv = document.getElementById('cvv').value;

            if (numTickets < 1) {
                alert('Please enter a valid number of tickets.');
                e.preventDefault();
            }

            if (!/^\d{16}$/.test(cardNumber.replace(/\s/g, ''))) {
                alert('Please enter a valid 16-digit card number.');
                e.preventDefault();
            }

            if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                alert('Please enter a valid expiry date (MM/YY).');
                e.preventDefault();
            }

            if (!/^\d{3,4}$/.test(cvv)) {
                alert('Please enter a valid CVV (3 or 4 digits).');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
