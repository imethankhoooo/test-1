<?php


$conn = new mysqli("localhost", "root", "", "php-assginment");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get events
function getEvents($conn) {
    $sql = "SELECT * FROM event ORDER BY event_date, start_time";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

// Function to get member count
function getMemberCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM member";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Function to get admin count
function getAdminCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM admin";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get data
$events = getEvents($conn);
$totalMembers = getMemberCount($conn);
$adminMembers = getAdminCount($conn);
$totalEvents = count($events);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .timetable {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        .timetable-event {
            background-color: #e6f3ff;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }
        .timetable-event img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Dashboard</h1>
        
        <div class="stats-container">
            <?php
            $stats = [
                ['Total Members', $totalMembers],
                ['Admin Members', $adminMembers],
                ['Total Events', $totalEvents]
            ];
            
            foreach ($stats as $stat) {
                echo "<div class='stat-card'>";
                echo "<h3>{$stat[0]}</h3>";
                echo "<p>{$stat[1]}</p>";
                echo "</div>";
            }
            ?>
        </div>
        
        <div id="calendar">
            <h2>Event Calendar</h2>
            <div class="timetable">
                <?php
                foreach ($events as $event) {
                    echo "<div class='timetable-event'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($event['banner_image']) . "' alt='{$event['event_name']}'>";
                    echo "<h3>{$event['event_name']}</h3>";
                    echo "<p>{$event['event_date']}</p>";
                    echo "<p>{$event['start_time']} - {$event['end_time']}</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        
        <div class="chart-container">
            <h2>Monthly Revenue</h2>
            <div id="revenueChart"></div>
        </div>
        
        <div class="events-container">
            <h2>Upcoming Events</h2>
            <?php
            foreach ($events as $event) {
                echo "<div class='event-card'>";
                echo "<h3>{$event['event_name']}</h3>";
                echo "<div class='event-info'><img src='/api/placeholder/16/16' alt='Calendar'>{$event['event_date']}</div>";
                echo "<div class='event-info'><img src='/api/placeholder/16/16' alt='Clock'>{$event['start_time']}</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
        // ... (previous JavaScript code) ...

        // Update the generateCalendar function to use the PHP-generated timetable
        function generateCalendar(year, month) {
            // The calendar is now generated on the server-side in PHP
            // You can add any additional JavaScript functionality here if needed
        }

        // ... (rest of the JavaScript code) ...
    </script>
</body>
</html>
<?php
$conn->close();
?>