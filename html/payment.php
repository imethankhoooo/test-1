<?php
session_start();


$conn = new mysqli('localhost', 'root', '', 'php-assginment');


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



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_tickets'])&& isset($_POST['card_number'])) {

    $num_tickets = (int)$_POST['num_tickets'];
    $total_price = $event['fee'] * $num_tickets;
    $currentSeat = $event['seat'];


    $conn->begin_transaction();

    try {

        $sql = "INSERT INTO bookinginformation (event_id, member_id, paymentAmount, booking_date) VALUES (?, ?,?, CURRENT_TIMESTAMP)";
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for <?php echo htmlspecialchars($event['event_name']); ?></title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #2c3e50;
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
            font-weight: 600;
            color: white;
            text-decoration: none;
        }
        main {
            padding-top: 100px;
            min-height: calc(100vh - 150px);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .payment-section, .ticket-section {
            max-width: 800px;
            width: 100%;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .payment-form h2, .ticket-section h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2rem;
        }
        .payment-details {
            background-color: #ecf0f1;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        .payment-details p {
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
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }
        .submit-btn:hover {
            background-color: #34495e;
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
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 1rem 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .ticket-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
        }
        .ticket {
            background-color: #fff;
            border: 2px solid #3498db;
            border-radius: 10px;
            width: 700px;
            height: 220px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            position: relative;
        }
        .ticket-left {
            width: 75%;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f5f7fa 0%, #e0e5ec 100%);
            position: relative;
        }
        .ticket-right {
            width: 25%;
            background-color: #3498db;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .ticket-event-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .ticket-info {
            font-size: 1.1rem;
            color: #34495e;
            margin-bottom: 0.7rem;
            display: flex;
            justify-content: space-between;
        }
        .ticket-info strong {
            min-width: 100px;
            display: inline-block;
        }
        .ticket-number {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .ticket-price {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .ticket::before,
        .ticket::after,
        .ticket-right::before,
        .ticket-right::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background-color: #f4f7f6;
            border-radius: 50%;
        }
        .ticket::before {
            top: -20px;
            left: -20px;
        }
        .ticket::after {
            bottom: -20px;
            left: -20px;
        }
        .ticket-right::before {
            top: -20px;
            right: -20px;
        }
        .ticket-right::after {
            bottom: -20px;
            right: -20px;
        }
        .ticket-divider {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 75%;
            width: 4px;
            background-image: linear-gradient(to bottom, #3498db 50%, transparent 50%);
            background-size: 4px 20px;
            background-repeat: repeat-y;
        }
        @media (max-width: 768px) {
            .ticket {
                width: 100%;
            }
        }
        .ticket {
    background-color: #fff;
    border: none;
    border-radius: 8px;
    width: 650px;
    height: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    position: relative;
    margin-bottom: 2rem;
    overflow: hidden;
}

.ticket-left {
    width: 75%;
    padding: 20px;
    background: linear-gradient(135deg, #f4f4f4 0%, #eaeaea 100%);
    position: relative;
    border-right: 1px dashed #ccc;
}

.ticket-right {
    width: 25%;
    background-color: #ff5733;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    position: relative;
}

.ticket-event-name {
    font-size: 1.8rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.ticket-info {
    font-size: 1rem;
    color: #666;
    margin-bottom: 8px;
}

.ticket-info strong {
    display: inline-block;
    min-width: 80px;
}

.ticket-number {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.ticket-price {
    font-size: 1.5rem;
    font-weight: bold;
}

.ticket::before, .ticket::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: #fff;
    border-radius: 50%;
    box-shadow: 0 0 0 1px #ccc;
}

.ticket::before {
    top: 50%;
    left: -10px;
    transform: translateY(-50%);
}

.ticket::after {
    top: 50%;
    right: -10px;
    transform: translateY(-50%);
}

.ticket-divider {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 75%;
    width: 4px;
    background-image: linear-gradient(to bottom, #ff5733 50%, transparent 50%);
    background-size: 4px 20px;
    background-repeat: repeat-y;
}

@media (max-width: 768px) {
    .ticket {
        width: 100%;
    }
}
    </style>
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
                        <input type="number" id="num_tickets" name="num_tickets" required min="1" value="1">
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
