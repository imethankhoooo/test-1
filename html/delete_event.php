<?php
session_start();
$conn = new mysqli("localhost", "root", "", "php-assignment");

if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error);
}


function isAdmin() {
  
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }

    global $conn;
    $sql = "SELECT admin_id FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $_SESSION['admin_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
    }

    return false;
}

if (!isAdmin()) {
    die("Access denied. You must be an admin to delete events.");
}

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);


    $conn->begin_transaction();

    try {
     
        $delete_tickets_sql = "DELETE t FROM ticket t
                               JOIN bookingInformation bi ON t.booking_id = bi.booking_id
                               WHERE bi.event_id = ?";
        $delete_tickets_stmt = $conn->prepare($delete_tickets_sql);
        $delete_tickets_stmt->bind_param("i", $event_id);
        $delete_tickets_stmt->execute();
        $delete_tickets_stmt->close();

        $delete_bookings_sql = "DELETE FROM bookingInformation WHERE event_id = ?";
        $delete_bookings_stmt = $conn->prepare($delete_bookings_sql);
        $delete_bookings_stmt->bind_param("i", $event_id);
        $delete_bookings_stmt->execute();
        $delete_bookings_stmt->close();

       
        $delete_items_sql = "DELETE FROM item WHERE event_id = ?";
        $delete_items_stmt = $conn->prepare($delete_items_sql);
        $delete_items_stmt->bind_param("i", $event_id);
        $delete_items_stmt->execute();
        $delete_items_stmt->close();

       
        $delete_event_sql = "DELETE FROM event WHERE event_id = ?";
        $delete_event_stmt = $conn->prepare($delete_event_sql);
        $delete_event_stmt->bind_param("i", $event_id);
        $delete_event_stmt->execute();
        $delete_event_stmt->close();

     
        $conn->commit();

       header("Location:administratorhome.php");
    } catch (Exception $e) {
       
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Error deleting event: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No event ID provided."]);
}

$conn->close();
?>