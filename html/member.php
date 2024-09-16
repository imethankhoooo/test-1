<?php

$conn = new mysqli("localhost", "root", "", "php-assignment");


if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error);
}


$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;


if (isset($_POST['delete'])) {

    $conn->begin_transaction();

    try {

        $booking_sql = "SELECT bi.booking_id, bi.event_id, COUNT(t.ticket_id) AS booked_seats, e.seat AS current_seats
                        FROM bookingInformation bi
                        JOIN event e ON bi.event_id = e.event_id
                        JOIN ticket t ON bi.booking_id = t.booking_id
                        WHERE bi.member_id = ?
                        GROUP BY bi.event_id, bi.booking_id, e.seat";
        $booking_stmt = $conn->prepare($booking_sql);
        $booking_stmt->bind_param("i", $member_id);
        $booking_stmt->execute();
        $booking_result = $booking_stmt->get_result();

        while ($booking = $booking_result->fetch_assoc()) {
          
            $new_seat_count = $booking['current_seats'] + $booking['booked_seats'];
            $update_seat_sql = "UPDATE event SET seat = ? WHERE event_id = ?";
            $update_seat_stmt = $conn->prepare($update_seat_sql);
            $update_seat_stmt->bind_param("ii", $new_seat_count, $booking['event_id']);
            $update_seat_stmt->execute();
            $update_seat_stmt->close();


            $delete_tickets_sql = "DELETE FROM ticket WHERE booking_id = ?";
            $delete_tickets_stmt = $conn->prepare($delete_tickets_sql);
            $delete_tickets_stmt->bind_param("i", $booking['booking_id']);
            $delete_tickets_stmt->execute();
            $delete_tickets_stmt->close();
        }

        $booking_stmt->close();


        $delete_bookings_sql = "DELETE FROM bookingInformation WHERE member_id = ?";
        $delete_bookings_stmt = $conn->prepare($delete_bookings_sql);
        $delete_bookings_stmt->bind_param("i", $member_id);
        $delete_bookings_stmt->execute();
        $delete_bookings_stmt->close();

  
        $delete_member_sql = "DELETE FROM member WHERE member_id = ?";
        $delete_member_stmt = $conn->prepare($delete_member_sql);
        $delete_member_stmt->bind_param("i", $member_id);
        $delete_member_stmt->execute();
        $delete_member_stmt->close();


        $conn->commit();

        echo "<script>alert('The user and related booking information have been successfully deleted and the related event seats have been updated'); window.location.href = 'index.php';</script>";
        exit;
    } catch (Exception $e) {

        $conn->rollback();
        echo "<script>alert('Delete Failed: " . $conn->error . "');</script>";
    }
}


$sql = "SELECT * FROM member WHERE member_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $member = $result->fetch_assoc();
} else {
    die("No Member Information Found");
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Information</title>
    <link rel="stylesheet" href="../css/member.css">
    <link rel="stylesheet" href="../css/color.css">
    
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($member['avatar']) ?>" alt="Member avatar" class="avatar">
            <h1><?= htmlspecialchars($member['name']) ?></h1>
            <p class="username">@<?= htmlspecialchars($member['username']) ?></p>
        </div>
        
        <div class="profile-details">
            <div class="detail-item">
                <p class="detail-label">Gender:</p>
                <p class="detail-value"><?= htmlspecialchars($member['gender'] ) ?></p>
            </div>
            <div class="detail-item">
                <p class="detail-label">Email:</p>
                <p class="detail-value"><?= htmlspecialchars($member['email']) ?></p>
            </div>
            <div class="detail-item">
                <p class="detail-label">PhoneNo:</p>
                <p class="detail-value"><?= htmlspecialchars($member['phone'] ) ?></p>
            </div>
            <div class="detail-item">
                <p class="detail-label">Address:</p>
                <p class="detail-value"><?= htmlspecialchars($member['address'] ) ?></p>
            </div>
        </div>
        
        <div class="bio">
            <h2>Personal Information</h2>
            <p><?= nl2br(htmlspecialchars($member['bio'] ?? 'This Person Have Not any information')) ?></p>
        </div>
        
        <div class="experience">
            <h2>Experience</h2>
            <p><?= nl2br(htmlspecialchars($member['experience'] ?? 'No relevant experience')) ?></p>
        </div>
        
        <div class="social-media">
            <h2>Sosial Media</h2>
            <p><?= nl2br(htmlspecialchars($member['socialMedia'] ?? 'No social media information')) ?></p>
        </div>
        
        <div class="button-container">
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this user? This operation will also delete all reservation information of this user and update the number of seats for related activities. This operation cannot be undone.');">
                <button type="submit" name="delete" class="delete-button">Delete Member</button>
            </form>
        </div>
    </div>
</body>
</html>