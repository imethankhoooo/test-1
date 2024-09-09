<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking And Ticket Information</title>
    <link rel="stylesheet" href="../css/booking_information.css">
</head>
<body>
    <div class="container">
        <?php


     
        $conn = new mysqli("localhost", "root", "", "php-assginment");
       
     
        if ($conn->connect_error) {
            die("<p class='error'>连接失败: " . $conn->connect_error . "</p>");
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_ticket'])) {
            $ticket_id = intval($_POST['delete_ticket']);
            $stmt = $conn->prepare("DELETE FROM ticket WHERE ticket_id = ?");
            $stmt->bind_param("i", $ticket_id);
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Ticket Delete Successful</p>";
            } else {
                echo "<p style='color: red;'>Ticket Delete Failed: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    
        $member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;

        if ($member_id > 0) {
      
            $stmt = $conn->prepare("SELECT name, username, gender, email, avatar FROM member WHERE member_id = ?");
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $member = $result->fetch_assoc();

            if ($member) {
                echo "<div class='member-info'>";
                echo "<div class='avatar'>";
                if ($member['avatar']) {
                    $avatar = htmlspecialchars($member['avatar']);
                    echo "<img src='$avatar' alt='Avatar'>";
                } else {
                    echo "<div style='width:100%;height:100%;background-color:#3498db;display:flex;align-items:center;justify-content:center;color:white;font-size:36px;font-weight:bold;'>" . substr($member['name'], 0, 1) . "</div>";
                }
                echo "</div>";
                echo "<div class='member-details'>";
                echo "<h1 class='member-name'>" . htmlspecialchars($member['name']) . "</h1>";
                echo "<p class='member-username'>@" . htmlspecialchars($member['username']) . "</p>";
                echo "<p><strong>Gender:</strong> " . htmlspecialchars($member['gender']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($member['email']) . "</p>";
                echo "</div>";
                echo "</div>";

             
                echo "<h2>Payment History</h2>";
                echo "<table>";
                echo "<thead><tr><th>Invoice ID</th><th>Event ID</th><th>Payment Amount</th><th>Booking Date</th></tr></thead>";
                echo "<tbody>";

                $stmt = $conn->prepare("SELECT booking_id, event_id, paymentAmount, booking_date FROM bookingInformation WHERE member_id = ?");
                $stmt->bind_param("i", $member_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["booking_id"] . "</td>";
                        echo "<td>" . $row["event_id"] . "</td>";
                        echo "<td>￥" . number_format($row["paymentAmount"], 2) . "</td>";
                        echo "<td>" . $row["booking_date"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>No Payment History Found</td></tr>";
                }

                echo "</tbody></table>";

   
                echo "<h2>Current Ticket Information</h2>";
            
                $stmt = $conn->prepare("
                    SELECT t.ticket_id, t.booking_id, t.event_id, t.issue_date, 
                           e.event_name, e.event_date, e.banner_image
                    FROM ticket t
                    JOIN event e ON t.event_id = e.event_id
                    WHERE t.member_id = ?
                ");
                $stmt->bind_param("i", $member_id);
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='event-info'>";
                        if ($row['banner_image']) {
                            $imgSrc = htmlspecialchars($row['banner_image']);
                            echo "<img src='$imgSrc' alt='Event Banner' class='event-image'>";
                        } else {
                            echo "<div class='event-image' style='background-color: #ddd; display: flex; align-items: center; justify-content: center;'>No Image</div>";
                        }
                        echo "<div class='event-details'>";
                        echo "<div class='event-name'>" . htmlspecialchars($row['event_name']) . "</div>";
                        echo "<div>Event Date: " . $row['event_date'] . "</div>";
                        echo "<div>Ticket ID: " . $row['ticket_id'] . "</div>";
                        echo "<div>Invoice ID: " . $row['booking_id'] . "</div>";
                        echo "<div>Booking Date: " . $row['issue_date'] . "</div>";
                        echo "</div>";
                        echo "<button class='delete-btn' onclick='deleteTicket(" . $row['ticket_id'] . ")'>Delete</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='text-align:center;'>No ticket Found</p>";
                }
    
            } else {
                echo "<p class='error'>Invalid Member_id</p>";
            }
    

            $conn->close();
        }  
        ?>
        
    </div>
    <script>
         function deleteTicket(ticketId) {
    if (confirm('Are You Sure To Delete This Ticket?')) {
        var form = document.createElement('form');
        form.method = 'post';
        form.style.display = 'none';

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_ticket';
        input.value = ticketId;

        form.appendChild(input);
        document.body.appendChild(form);

        form.submit();
    }
}
    </script>
</body>
</html>