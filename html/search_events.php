<?php
$conn = new mysqli('localhost', 'root', '', 'php-assginment');
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'none';

$sql = "SELECT e.event_id, e.banner_image, e.event_name, e.location, e.event_date,e.fee, 
               COUNT(t.ticket_id) AS ticket_count
        FROM event e
        LEFT JOIN ticket t ON e.event_id = t.event_id
        WHERE 1=1";

if ($search !== "") {
    $sql .= " AND e.event_name LIKE '%$search%'";
}

$sql .= " GROUP BY e.event_id";

switch ($filter) {
    case 'recent':
        $sql .= " ORDER BY e.event_date ASC";
        break;
    case 'popular':
        $sql .= " ORDER BY ticket_count DESC";
        break;
    case 'price_low':
        $sql .= " ORDER BY e.fee ASC";
        break;
    default:
        // No specific ordering
        break;
}

$result = $conn->query($sql);
$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);

$conn->close();
?>