<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/addEventPage.css">
    <link rel="stylesheet" href="../css/page3.css">


</head>

<body>
    
    <?php
    session_start();
   
    $conn = new mysqli('localhost', 'root', '', 'php-assginment');

   
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $venue = $_POST['Venue'];
        $event_date = $_POST['Date'];
        $event_startTime = $_POST['StartTime'];
        $event_endTime = $_POST['EndTime'];
        $fee = $_POST['Fee'];
        $host = $_POST['Host'];
        $host_phone = $_POST['Phone'];
        $precautions = $_POST['Precautions'];
        $seat = $_POST['seat'];

        $bannerImgPath = null;

        if (isset($_FILES['eventImgInput']['tmp_name']) && !empty($_FILES['eventImgInput']['tmp_name'])) {
            $bannerImgPath = $uploadDir . basename($_FILES['eventImgInput']['name']);
            if (move_uploaded_file($_FILES['eventImgInput']['tmp_name'], $bannerImgPath)) {
            } else {
                die("上传 banner 图片失败，检查文件上传是否正确。");
            }
        } else {
            echo "未检测到文件上传。<br>";
        }

    
        $stmt = $conn->prepare("INSERT INTO event (event_name, description, location, event_date, start_time, end_time, fee, seat, event_host, phone, note, banner_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $title, $content, $venue, $event_date, $event_startTime, $event_endTime, $fee, $seat, $host, $host_phone, $precautions, $bannerImgPath);

        if ($stmt->execute()) {
            $event_id = $conn->insert_id; 

            if (!empty($_POST['thingContent'])) {
                foreach ($_POST['thingContent'] as $index => $thingContent) {
                   
                    if (!empty($thingContent) && !empty($_FILES['file-input']['tmp_name'][$index])) {
                        $thing_image = null;

                        if (!empty($_FILES['file-input']['tmp_name'][$index])) {
                            $thing_image = file_get_contents($_FILES['file-input']['tmp_name'][$index]);
                        }
                        $thingImgPath = $uploadDir . basename($_FILES['file-input']['name'][$index]);
                        move_uploaded_file($_FILES['file-input']['tmp_name'][$index], $thingImgPath);

                     
                        $stmtItem = $conn->prepare("INSERT INTO item (event_id, description, logo) VALUES (?, ?, ?)");
                        $stmtItem->bind_param("iss", $event_id, $thingContent, $thingImgPath);
                        $stmtItem->execute();
                    }
                }
            }

           
        } else {
            echo "error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <aside>
        <div class="navigation">
            <div class="menuToggle"></div>
            <ul>
                <li class="list active" style="--clr:red;">
                    <a onclick="switchPage('adminPage1')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 131631.png">
                        </span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list" style="--clr:black;">
                    <a onclick="switchPage('adminPage2')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133129.png"></span>
                        <span class="text">Member Information</span>
                    </a>
                </li>
                <li class="list" style="--clr:green;">
                    <a onclick="switchPage('adminPage3')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133525.png"
                                alt=""></span>
                        <span class="text">Search</span>
                    </a>
                </li>

                <li class="list" style="--clr:rgb(255, 153, 51);">
                    <a onclick="switchPage('adminPage4')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-07-06 223611.png">
                        </span>
                        <span class="text">Modify</span>
                    </a>
                </li>
                <li class="list" style="--clr:blue;">
                    <a onclick="switchPage('adminPage5')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133929.png"
                                alt=""></span>
                        <span class="text">Add</span>
                    </a>
                </li>

            </ul>
        </div>
    </aside>

    <div class="Page adminPage1 ">
    <?php
$conn = new mysqli("localhost", "root", "", "php-assginment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function getEvents($conn) {
    $sql = "SELECT COUNT(*) as count  FROM event ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        return $row['count'];
        
    }
    return 0;
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
    $sql = "SELECT COUNT(*) as count FROM admin_member";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}
// Function to get events for the next week
function getWeekEvents($conn) {
    $sql = "SELECT * FROM event WHERE event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY event_date, start_time";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

// Function to get upcoming events (more than a week in the future)
function getUpcomingEvents($conn) {
    $sql = "SELECT * FROM event WHERE event_date > DATE_ADD(CURDATE(), INTERVAL 7 DAY) ORDER BY event_date, start_time LIMIT 5";
    $result = $conn->query($sql);
    $events = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}

// Function to get monthly revenue
function getMonthlyRevenue($conn) {
    $sql = "SELECT MONTH(booking_date) as month, SUM(paymentAmount) as revenue FROM bookingInformation WHERE YEAR(booking_date) = YEAR(CURDATE()) GROUP BY MONTH(booking_date) ORDER BY month";
    $result = $conn->query($sql);
    $revenue = array_fill(0, 12, 0); // Initialize array with 12 zeros
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $revenue[$row['month'] - 1] = $row['revenue'];
        }
    }
    return $revenue;
}

// Get data
$weekEvents = getWeekEvents($conn);
$upcomingEvents = getUpcomingEvents($conn);
$totalMembers = getMemberCount($conn);
$adminMembers = getAdminCount($conn);
$totalEvents = getEvents($conn);
$monthlyRevenue = array_map('floatval', getMonthlyRevenue($conn));

?>
<style>
       .adminPage1 {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }
        .dashboard {
            max-width: 1500px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page1H1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 30px;
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .stat-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            width: 30%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .stat-card h3 {
            margin: 0 0 15px 0;
            color: #34495e;
            font-size: 1.2em;
        }
        .stat-card p {
            font-size: 2em;
            font-weight: bold;
            margin: 0;
            color: #3498db;
        }
        .stat-card svg {
            width: 24px;
            height: 24px;
            margin-bottom: 10px;
            fill: #3498db;
        }
        .chart-container {
            margin-bottom: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out 0.4s;
            animation-fill-mode: both;
        }
        .Page1H2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
        }
        .week-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .day-column {
            background-color: #ecf0f1;
            border-radius: 8px;
            padding: 15px;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }
        .day-column:nth-child(1) { animation-delay: 0.5s; }
        .day-column:nth-child(2) { animation-delay: 0.6s; }
        .day-column:nth-child(3) { animation-delay: 0.7s; }
        .day-column:nth-child(4) { animation-delay: 0.8s; }
        .day-column:nth-child(5) { animation-delay: 0.9s; }
        .day-column:nth-child(6) { animation-delay: 1s; }
        .day-column:nth-child(7) { animation-delay: 1.1s; }
        .day-column h3 {
            margin-top: 0;
            text-align: center;
            color: #34495e;
            font-size: 1.2em;
        }
        .day-event {
            background-color: #3498db;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .day-event:hover {
            transform: translateY(-3px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .day-event h4 {
            margin: 0 0 5px 0;
            font-size: 1em;
        }
        .day-event p {
            margin: 0;
            font-size: 0.9em;
        }
        #revenueChart {
            width: 100%;
            height: 300px;
            position: relative;
        }
        .bar {
            position: absolute;
            bottom: 0;
            width: 7%;
            background-color: #2ecc71;
            transition: height 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-radius: 5px 5px 0 0;
        }
        .bar-label {
            position: absolute;
            bottom: -25px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }
        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .event-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }
        .event-card:nth-child(1) { animation-delay: 1.2s; }
        .event-card:nth-child(2) { animation-delay: 1.3s; }
        .event-card:nth-child(3) { animation-delay: 1.4s; }
        .event-card:nth-child(4) { animation-delay: 1.5s; }
        .event-card:nth-child(5) { animation-delay: 1.6s; }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .event-card h3 {
            margin: 0 0 15px 0;
            color: #34495e;
            font-size: 1.2em;
        }
        .event-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: #7f8c8d;
        }
        .event-info svg {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            fill: #7f8c8d;
        }
        .stat-card img {
            width: 24px;
            height: 24px;
            margin-bottom: 10px;
        }
        .event-info img {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        .day-event {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .day-event:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .day-event img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .day-event h4 {
            margin: 0 0 5px 0;
            font-size: 1em;
            color: #34495e;
        }
        .day-event p {
            margin: 0;
            font-size: 0.9em;
            color: #7f8c8d;
        }
        .tooltip {
        position: absolute;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 1000;
    }
    </style>
     <div class="dashboard">
        <h1 class="page1H1">Dashboard</h1>
        <div class="stats-container">
            <?php
            $stats = [
                ['Total Members', $totalMembers, '../Img/person-outline.svg'],
                ['Admin Members', $adminMembers, '../Img/person-circle-outline.svg'],
                ['Total Events', $totalEvents, '../Img/calendar-clear-outline.svg']
            ];
            
            foreach ($stats as $stat) {
                echo "<div class='stat-card'>";
                echo "<img src='{$stat[2]}' alt='{$stat[0]} icon'>";
                echo "<h3>{$stat[0]}</h3>";
                echo "<p>{$stat[1]}</p>";
                echo "</div>";
            }
            ?>
        </div>
        
        <div class="chart-container">
            <h2 class="Page1H2">Monthly Revenue</h2>
            <div id="revenueChart"></div>
            <div id="tooltip" class="tooltip"></div>
        </div>
        
        <div id="calendar">
            <h2 class="Page1H2">This Week's Events</h2>
            <div class="week-calendar">
                <?php
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                foreach ($days as $dayIndex => $dayName) {
                    echo "<div class='day-column'>";
                    echo "<h3>$dayName</h3>";
                    foreach ($weekEvents as $event) {
                        $eventDate = new DateTime($event['event_date']);
                        if ($eventDate->format('w') == $dayIndex) {
                            echo "<div class='day-event'>";
                         
                                echo "<img src='{$event['banner_image']}' alt='{$event['event_name']}' />";
                            
                            echo "<h4>{$event['event_name']}</h4>";
                            echo "<p>{$event['start_time']} - {$event['end_time']}</p>";
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        
        <h2 class="Page1H2">Upcoming Events</h2>
        <div class="events-container">
            <?php
            foreach ($upcomingEvents as $event) {
                echo "<div class='event-card'>";
                echo "<h3>{$event['event_name']}</h3>";
                echo "<div class='event-info'><svg viewBox='0 0 24 24'><path d='M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z'/></svg>{$event['event_date']}</div>";
                echo "<div class='event-info'><svg viewBox='0 0 24 24'><path d='M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z'/><path d='M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z'/></svg>{$event['start_time']}</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script>
    // Monthly Revenue Chart
    var revenueData = <?php echo json_encode(array_map('floatval', $monthlyRevenue)); ?>;
var chartContainer = document.getElementById('revenueChart');
var tooltip = document.getElementById('tooltip');
var maxRevenue = Math.max(...revenueData);
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

for (var i = 0; i < revenueData.length; i++) {
    var bar = document.createElement('div');
    bar.className = 'bar';
    bar.style.left = (i * 8.33) + '%';
    bar.style.height = (revenueData[i] / maxRevenue * 100) + '%';
    
    var label = document.createElement('div');
    label.className = 'bar-label';
    label.textContent = months[i];
    
    bar.appendChild(label);
    chartContainer.appendChild(bar);

    bar.addEventListener('mousemove', function(event) {
        var index = Array.from(this.parentNode.children).indexOf(this);
        tooltip.style.opacity = '1';
        
        var rect = chartContainer.getBoundingClientRect();
        var tooltipX = event.clientX - rect.left + 10;
        var tooltipY = event.clientY - rect.top - 25;
        
        if (tooltipX + tooltip.offsetWidth > rect.width) {
            tooltipX = event.clientX - rect.left - tooltip.offsetWidth - 10;
        }
        if (tooltipY < 0) {
            tooltipY = event.clientY - rect.top + 10;
        }
        
        tooltip.style.left = tooltipX + 'px';
        tooltip.style.top = tooltipY + 'px';
        
        var formattedRevenue = parseFloat(revenueData[index] || 0).toFixed(2);
        tooltip.textContent = months[index] + ': $' + formattedRevenue;
    });

    bar.addEventListener('mouseleave', function() {
        tooltip.style.opacity = '0';
    });
}
</script>
    </div>
    <div class="Page adminPage2 hidden">
        <style>

.adminPage2 {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 30px;
}

.search-container {
    margin-bottom: 40px;
    text-align: center;
}

#searchMember {
    width: 80%;
    max-width: 500px;
    padding: 15px 25px;
    border: none;
    border-radius: 50px;
    font-size: 18px;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    background-color: white;
}

#searchMember:focus {
    box-shadow: 0 4px 20px rgba(74, 144, 226, 0.4);
}


.member-card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    padding: 20px;
}

.member-card {
    background-color: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
}

.member-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}


.member-card img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    margin: 30px auto 20px;
    border: 5px solid #f0f0f0;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.member-info {
    padding: 0 25px 25px;
    text-align: center;
    flex-grow: 1;
}

.member-info h2 {
    margin: 0 0 10px;
    color: #333;
    font-size: 1.6em;
    font-weight: 600;
}

.member-info p {
    margin: 0;
    color: #666;
    font-size: 1em;
}


.view-button {
    display: block;
    width: calc(100% - 50px);
    margin: 20px auto;
    padding: 15px;
    background-color: #4a90e2;
    color: white;
    text-align: center;
    text-decoration: none;
    border: none;
    border-radius: 50px;
    font-size: 1em;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.view-button:hover {
    background-color: #3a7bc8;
    box-shadow: 0 5px 15px rgba(74, 144, 226, 0.4);
}
        </style>
        <div class="search-container">
            <input type="text" id="searchMember" placeholder="Search users..." value="">
        </div>
        <div class="member-card-container" id="memberCardContainer">
            <?php

            $conn = new mysqli('localhost', 'root', '', 'php-assginment');


            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }


            $sql = "SELECT member_id, name, email, avatar FROM member";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="member-card">';
                    echo '<img src="' . htmlspecialchars($row['avatar']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<div class="member-info">';
                    echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
                    echo '<p>' . htmlspecialchars($row['email']) . '</p>';
                    echo '</div>';
                    echo '<a href="member.php?id=' . htmlspecialchars($row['member_id']) . '" class="view-button">View Profile</a>';
                    echo '</div>';
                }
            } else {
                echo "没有找到用户";
            }

            $conn->close();
            ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const userCards = document.querySelectorAll('.member-card');

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    userCards.forEach(card => {
                        const userName = card.querySelector('h2').textContent.toLowerCase();
                        const userEmail = card.querySelector('p').textContent.toLowerCase();

                        if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        </script>

    </div>
    <div class="Page adminPage3 hidden">
    <?php

$conn = new mysqli('localhost', 'root', '', 'php-assginment');



if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$sql = "SELECT member_id, name, email,  avatar FROM member";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>
<style>
        .adminPage3 {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .panel-title {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: box-shadow 0.3s ease;
        }

        .search-box:focus-within {
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus {
            border-color: #3498db;
            outline: none;
        }

        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .user-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            color: #3498db;
            font-size: 32px;
            overflow: hidden;
            position: relative;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, transparent 30%, rgba(0,0,0,0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .user-card:hover .user-avatar::after {
            opacity: 1;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            text-align: center;
        }

        .user-email {
            color: #7f8c8d;
            margin-bottom: 10px;
            text-align: center;
        }

        .user-role {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .user-card:hover .user-role {
            background-color: #2980b9;
        }

        .view-profile {
            display: block;
            background-color: #2ecc71;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .view-profile:hover {
            background-color: #27ae60;
            transform: scale(1.05);
        }
    </style>
    <div class="container">
        <h1 class="panel-title">用户管理面板</h1>
        <div class="search-box">
            <input type="text" id="searchUser" class="search-input" placeholder="搜索用户...">
        </div>
        <div class="user-grid" id="userGrid">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="user-avatar">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    <a href="booking_information.php?member_id=<?php echo htmlspecialchars($user['member_id']); ?>" class="view-profile">查看资料</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchUser');
            const userCards = document.querySelectorAll('.user-card');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                userCards.forEach(card => {
                    const userName = card.querySelector('.user-name').textContent.toLowerCase();
                    const userEmail = card.querySelector('.user-email').textContent.toLowerCase();

                    if (userName.includes(searchTerm) || userEmail.includes(searchTerm) ) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
    </div>
    <div class="Page adminPage4 hidden">
        <div class="searchEvent-container">
            <input type="text" id="searchInput" class="searchEvent" placeholder="Search events...">
        </div>
        <div class="eventCard-Container">

            <?php
 

            $conn = new mysqli('localhost', 'root', '', 'php-assginment');

   
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }

 
            $sql = "SELECT event_id, banner_image, event_name, location FROM event";
            $result = $conn->query($sql);


            if ($result === false) {
                die("查询失败: " . $conn->error);
            }

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $event_id = $row['event_id'];  
                    echo '<div class="eventCard">';
                    $imgSrc = htmlspecialchars($row['banner_image']);


                    echo '<img class="eventCardImage" src="' . $imgSrc . '" alt="Event Image">';
                    echo '<div class="eventCardInfo">';
                    echo '<h2>' . htmlspecialchars($row['event_name']) . '</h2>';
                    echo '<p>' . nl2br(htmlspecialchars($row['location'])) . '</p><br>';
                    echo '<a href="Event.php?event_id=' . htmlspecialchars($event_id) . '" class="button">View</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "0 结果";
            }

            $conn->close();
            ?>

        </div>

    </div>
    <div class="Page adminPage5 hidden">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post' enctype="multipart/form-data">

            <div class="eventImgCointainer" onclick="triggerFileInput('eventImgInput')">
                <input type="file" id="eventImgInput" class="eventImgInput" name="eventImgInput" accept="image/*" style="display:none;" />
                <img class="plus-sign" src="../Img/Screenshot 2024-06-08 133929.png">
            </div>
            <div class="contentBlock">
                <h3 class="eventType">Special Event</h3>
                <input class="title eventInformation" name="title" placeholder="Title:">
                <textarea class="content eventInformation" name="content" rows="4" cols="50"></textarea>
                <span class="line"></span>
                <table class="inputTable">

                    <tr>
                        <td><label class="eventInformation" for="Date">Date :</label></td>
                        <td><input name="Date" type="date"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Time">Start Time :</label></td>
                        <td><input name="StartTime" type="time"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Time">End Time :</label></td>
                        <td><input name="EndTime" type="time"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Fee">Fee :</label></td>
                        <td><input name="Fee" type="number" step="0.01"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Host">Event host :</label></td>
                        <td><input name="Host"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Host">Host Phone number :</label></td>
                        <td><input name="Phone"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Venue">Venue :</label></td>
                        <td><textarea name="Venue" col="30" rows="3"></textarea></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Seat">Available Seat</label></td>
                        <td><input name="seat" type="number"></td>
                    </tr>

                    <tr>
                        <td><label class="eventInformation">Precautions : </label></td>
                        <td><textarea name="Precautions" cols="30" rows="3"></textarea></td>
                    </tr>
                </table>
                <span class="line"></span>
                <h3 class="thingToBring">Thing to bring</h3>
                <div class="think-Container">
                    <div class="thingIcon">
                        <input type="file" id="file-input0" class="file-input" accept="image/*" style="display:none;" name="file-input[]" />
                        <div class="img-box" id="img-box0" onclick="triggerFileInput('file-input0')">
                            <img src="../Img/Screenshot 2024-06-08 133929.png" alt="addImg" class="plus-sign"
                                id="plus-sign0">
                        </div>
                        <input name="thingContent[]" class="thingContent">
                    </div>
                </div>
                <div class="button-container">
                    <input class="button" type="submit" name="submit" value="Add">
                    <input class="button" type="reset" name="reset" value="Reset">
                </div>
            </div>
        </form>
    </div>


</body>
<script src="../js/page.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.querySelector('.think-Container');
        const thingIcons = container.children;
        let currentRowTop = thingIcons[0].offsetTop;
        let rowThingIcons = [];
        let maxWidth;

        Array.from(thingIcons).forEach((thingIcon, index) => {
            if (thingIcon.offsetTop !== currentRowTop) {
                maxWidth = (100 / rowThingIcons.length) + '%';
                rowThingIcons.forEach(rowThingIcon => rowThingIcon.style.maxWidth = maxWidth);
                rowThingIcons = [];
                currentRowTop = thingIcon.offsetTop;
            }
            rowThingIcons.push(thingIcon);

            // Handle last row
            if (index === thingIcons.length - 1) {
                maxWidth = (100 / rowThingIcons.length) + '%';
                rowThingIcons.forEach(rowThingIcon => rowThingIcon.style.maxWidth = maxWidth);
            }
        });
    });

    function triggerFileInput(fileInputId) {
        document.getElementById(fileInputId).click();
    }
 i = 0;
    let changeImg = 0;

    document.querySelector('.think-Container').addEventListener('change', function(event) {
        let target = event.target;
        if (target.classList.contains('file-input')) {
            const file = target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgBoxId = 'img-box' + target.id.replace('file-input', '');
                    const imgBox = document.getElementById(imgBoxId);
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'addImg';
                    img.id = 'addImg' + i;
                    const plusSignId = 'plus-sign' + target.id.replace('file-input', '');
                    const plusSign = document.getElementById(plusSignId);
                    plusSign.classList.add('hidden');
                    imgBox.appendChild(img);

                    if (changeImg == 0) {
                        createNewThingIcon();
                    } else {
                        changeImg--;
                    }
                }
                reader.readAsDataURL(file);
            }
        }
    });


    document.querySelector('.eventImgCointainer').addEventListener('change', function(event) {
        let target = event.target;

        const file = target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const eventImgCointainer = document.querySelector('.eventImgCointainer');
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'eventImg';
                var plussign = document.querySelector('.plus-sign');
                plussign.classList.add('hidden');
                eventImgCointainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    })

    function createNewThingIcon() {
        i++;
        const container = document.querySelector('.think-Container');
        const newThingIcon = document.createElement('div');
        newThingIcon.className = 'thingIcon';

        const newFileInput = document.createElement('input');
        newFileInput.type = 'file';
        newFileInput.id = 'file-input' + i;
        newFileInput.name = 'file-input[]';
        newFileInput.className = 'file-input';
        newFileInput.accept = 'image/*';
        newFileInput.style.display = 'none';

        const newImgBox = document.createElement('div');
        newImgBox.className = 'img-box';
        newImgBox.id = 'img-box' + i;
        newImgBox.onclick = function() {
            triggerFileInput('file-input' + i);
        };

        const newPlusSign = document.createElement('img');
        newPlusSign.src = '../Img/Screenshot 2024-06-08 133929.png';
        newPlusSign.alt = 'addImg';
        newPlusSign.className = 'plus-sign';
        newPlusSign.id = 'plus-sign' + i;

        const newThingContent = document.createElement('input');
        newThingContent.name = 'thingContent[]';
        newThingContent.className = 'thingContent';

        newImgBox.appendChild(newPlusSign);
        newThingIcon.appendChild(newFileInput);
        newThingIcon.appendChild(newImgBox);
        newThingIcon.appendChild(newThingContent);
        container.appendChild(newThingIcon);
    }


    let index = 0;
    let width = 0;
    let result = 0;
    const moreEventImg = document.querySelectorAll('.moreEventImg');

    function leftImg() {
        index--;
        if (index < 0) {
            index = moreEventImg.length - 1;
        }
        refresh();
    }

    function rightImg() {
        index++;
        if (index > moreEventImg.length - 1) {
            index = 0;
        }
        refresh();
    }

    function refresh() {
        const ImgContainer = document.querySelector('.ImgContainer');
        width = ImgContainer.clientWidth;
        result = (index * width) + "px";
        ImgContainer.style.right = result;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const eventCardContainer = document.querySelector('.eventCard-Container');
        let allEvents = [];
        let debounceTimer;

        function fetchEvents(searchTerm) {
            fetch(`search_events.php?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(events => {
                    allEvents = events;
                    displayEvents(events);
                })
                .catch(error => console.error('Error:', error));
        }

        function displayEvents(events) {
            eventCardContainer.innerHTML = ''; 
            events.forEach((event, index) => {
                const eventCard = createEventCard(event);
                eventCardContainer.appendChild(eventCard);
                setTimeout(() => {
                    eventCard.style.opacity = '1';
                    eventCard.style.transform = 'scale(1)';
                }, 50 * index);
            });
        }

        function createEventCard(event) {
            const card = document.createElement('div');
            card.className = 'eventCard';
            card.dataset.eventId = event.event_id;
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';

            const imgSrc = event.banner_image ? event.banner_image : 'path/to/default/image.jpg';

            card.innerHTML = `
            <img class="eventCardImage" src="${imgSrc}" alt="Event Image">
            <div class="eventCardInfo">
                <h2>${event.event_name}</h2>
                <p>${event.location}</p><br>
                <a href="Event.php?event_id=${event.event_id}" class="button">View</a>
            </div>
        `;

            return card;
        }

        function filterEvents(searchTerm) {
            if (searchTerm.trim() === '') {
                fetchEvents('');
            } else {
                fetchEvents(searchTerm);
            }
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterEvents(this.value);
            }, 300); 
        });


        fetchEvents('');
    });
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchMember');
        const userCardContainer = document.getElementById('memberCardContainer');
        let allUsers = [];
        let debounceTimer;

        function fetchMembers(searchTerm) {
            
            fetch(`search_member.php?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(members => {
                    allMembers = members;
                    displayMember(members);
                })
                .catch(error => console.error('Error:', error));
        }

        function displayMember(members) {
            userCardContainer.innerHTML = ''; 
            members.forEach((member, index) => {
                const memberCard = createUserCard(member);
                userCardContainer.appendChild(memberCard);
                setTimeout(() => {
                    memberCard.style.opacity = '1';
                    memberCard.style.transform = 'scale(1)';
                }, 50 * index);
            });
        }

        function createUserCard(member) {
            const card = document.createElement('div');
            card.className = 'member-card';
            card.dataset.userId = member.member_id;
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';

            const imgSrc = member.avatar ? member.avatar : '/api/placeholder/400/300';

            card.innerHTML = `
            <img src="${imgSrc}" alt="${member.name}">
            <div class="member-info">
                <h2>${member.name}</h2>
                <p>${member.email}</p>
            </div>
            <a href="user_profile.php?member_id=${member.member_id}" class="view-button">View Profile</a>
        `;

            return card;
        }

        function filterMembers(searchTerm) {
            if (searchTerm.trim() === '') {
                fetchMembers('');
            } else {
                fetchMembers(searchTerm);
            }
        }

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterMembers(this.value);

            }, 300);
        });


        fetchMembers('');
    });
</script>

</html>