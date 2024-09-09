<?php

$conn = new mysqli('localhost', 'root', '', 'php-assginment');


if ($conn->connect_error) {
    die("connect error: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

if ($search !== "") {
    $sql = "SELECT event_id, banner_image, event_name, location FROM event WHERE event_name LIKE '%$search%'";
} else {
    $sql = "SELECT event_id, banner_image, event_name, location FROM event";
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