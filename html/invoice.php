<?php
session_start();


$conn = new mysqli('localhost', 'root', '', 'php-assignment');


if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error);
}


$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;


$sql = "SELECT b.*, e.event_name, e.event_date, e.start_time, e.end_time, e.fee, m.name as member_name, m.email 
        FROM bookingInformation b 
        JOIN event e ON b.event_id = e.event_id 
        JOIN member m ON b.member_id = m.member_id 
        WHERE b.booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking Information No Found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice and Tickets - <?php echo htmlspecialchars($booking['event_name']); ?></title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #2c3e50;
        }
        .section {
            margin-top: 20px;
        }
        .download-section {
            margin-top: 30px;
        }
        .download-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .download-btn:hover {
            background-color: #2980b9;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-message">
            <h2>Payment SuccessFul</h2>
            <p>Your reservation is confirmed. Booking number：<?php echo $booking['booking_id']; ?></p>
        </div>
        
        <h1>Invoice for <?php echo htmlspecialchars($booking['event_name']); ?></h1>
        <div class="section">
            <table>
                <tr>
                    <th>Booking ID</th>
                    <td><?php echo $booking['booking_id']; ?></td>
                </tr>
                <tr>
                    <th>Event</th>
                    <td><?php echo $booking['event_name']; ?></td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td><?php echo $booking['event_date']; ?></td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td><?php echo $booking['start_time'] . ' - ' . $booking['end_time']; ?></td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td><?php echo $booking['member_name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $booking['email']; ?></td>
                </tr>
                <tr>
                    <th>Number of Tickets</th>
                    <td><?php echo $booking['num_tickets']; ?></td>
                </tr>
                <tr>
                    <th>Price per Ticket</th>
                    <td>$<?php echo $booking['fee']; ?></td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>$<?php echo ($booking['fee'] * $booking['num_tickets']); ?></td>
                </tr>
            </table>
        </div>
        
        <h1>Tickets</h1>
        <?php for ($i = 1; $i <= $booking['num_tickets']; $i++): ?>
            <div class="section">
                <h2>Ticket <?php echo $i; ?></h2>
                <table>
                    <tr>
                        <th>Event</th>
                        <td><?php echo $booking['event_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td><?php echo $booking['event_date']; ?></td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td><?php echo $booking['start_time'] . ' - ' . $booking['end_time']; ?></td>
                    </tr>
                    <tr>
                        <th>Ticket Number</th>
                        <td><?php echo $booking['booking_id'] . '-' . $i; ?></td>
                    </tr>
                    <tr>
                        <th>Customer</th>
                        <td><?php echo $booking['member_name']; ?></td>
                    </tr>
                </table>
            </div>
        <?php endfor; ?>
    </div>
</body>
</html>
