<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/addEventPage.css">
    <link rel="stylesheet" href="../css/page3.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/searchMemberInformation.css">
    <link rel="stylesheet" href="../css/searchMemberBooking.css">



</head>

<body>
    
    <?php
    session_start();
   
    $conn = new mysqli('localhost', 'root', '', 'php-assginment');

   if(!isset($_SESSION['admin_id'])){
    echo "<script>alert ('No session found. Please login first');window.location.href='login.php'; </script>";
   }
    if ($conn->connect_error) {
        die("connect error: " . $conn->connect_error);
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
                die("Failed to upload the banner image. Check whether the file was uploaded correctly.。");
            }
        } else {
            echo "No file upload detected.<br>";
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
        
        <div class="search-container">
            <input type="text" id="searchMember" placeholder="Search users..." value="">
        </div>
        <div class="member-card-container" id="memberCardContainer">
            
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
    die("connect error: " . $conn->connect_error);
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

    <div class="container">
        <h1 class="panel-title">member manage system</h1>
        <div class="search-box">
            <input type="text" id="searchUser" class="search-input" placeholder="search member...">
        </div>
        <div class="user-grid" id="userGrid">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="user-avatar">
                    <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                    <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    <a href="booking_information.php?member_id=<?php echo htmlspecialchars($user['member_id']); ?>" class="view-profile">View profile</a>
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
                die("Query failed: " . $conn->error);
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

<div class="Page adminPage5 hidden">
<h1>Create Special Event</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="image-upload">
                <label for="eventImgInput">Event Image:</label>
                <div class="image-preview" id="eventImagePreview">
                    <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="eventPreviewImg">
                </div>
                <input type="file" id="eventImgInput" name="eventImgInput" accept="image/*" style="display: none;">
            </div>

            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" id="title" name="title" required placeholder="Enter event title">
            </div>

            <div class="form-group">
                <label for="content">Event Description:</label>
                <textarea id="content" name="content" rows="4" required placeholder="Describe your event"></textarea>
            </div>

            <div class="form-group">
                <label for="Date">Event Date:</label>
                <input type="date" id="Date" name="Date" required>
            </div>

            <div class="form-group">
                <label for="StartTime">Start Time:</label>
                <input type="time" id="StartTime" name="StartTime" required>
            </div>

            <div class="form-group">
                <label for="EndTime">End Time:</label>
                <input type="time" id="EndTime" name="EndTime" required>
            </div>

            <div class="form-group">
                <label for="Fee">Event Fee:</label>
                <input type="number" id="Fee" name="Fee" step="0.01" required placeholder="Enter fee amount">
            </div>

            <div class="form-group">
                <label for="Host">Event Host:</label>
                <input type="text" id="Host" name="Host" required placeholder="Enter host name">
            </div>

            <div class="form-group">
                <label for="Phone">Host Phone Number:</label>
                <input type="tel" id="Phone" name="Phone" required placeholder="Enter phone number">
            </div>

            <div class="form-group">
                <label for="Venue">Event Venue:</label>
                <textarea id="Venue" name="Venue" rows="3" required placeholder="Enter venue details"></textarea>
            </div>

            <div class="form-group">
                <label for="Seat">Available Seats:</label>
                <input type="number" id="Seat" name="Seat" required placeholder="Enter number of seats">
            </div>

            <div class="form-group">
                <label for="Precautions">Event Precautions:</label>
                <textarea id="Precautions" name="Precautions" rows="3" required placeholder="Enter any precautions or guidelines"></textarea>
            </div>

            <h2>Things to Bring</h2>
            <div class="things-to-bring" id="thingsContainer">
                <div class="thing-item">
                    <div class="thing-image" onclick="triggerFileInput('file-input0')">
                        <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="previewImg0">
                    </div>
                    <input type="file" id="file-input0" name="file-input[]" accept="image/*" style="display: none;">
                    <input type="text" name="thingContent[]" placeholder="Item name">
                </div>
            </div>
            <button type="button" onclick="addThingItem()">Add Another Item</button>

            <div class="button-container">
                <button type="submit" name="submit" class="submit-btn">Create Event</button>
                <button type="reset" name="reset" class="reset-btn">Reset Form</button>
            </div>
        </form>
        <script>
        // ... (keep existing JavaScript) ...

        function addThingItem() {
            const container = document.getElementById('thingsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'thing-item';
            const itemIndex = container.children.length;
            newItem.innerHTML = `
                <div class="thing-image" onclick="triggerFileInput('file-input${itemIndex}')">
                    <img src="../Img/Screenshot 2024-06-08 133929.png" alt="Add Image" id="previewImg${itemIndex}">
                </div>
                <input type="file" id="file-input${itemIndex}" name="file-input[]" accept="image/*" style="display: none;">
                <input type="text" name="thingContent[]" placeholder="Item name">
            `;
            container.appendChild(newItem);
        }

        document.getElementById('eventImagePreview').addEventListener('click', function() {
            document.getElementById('eventImgInput').click();
        });

        document.getElementById('eventImgInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('eventPreviewImg').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        function triggerFileInput(inputId) {
            document.getElementById(inputId).click();
        }

        document.addEventListener('change', function(event) {
            if (event.target.type === 'file' && event.target.name === 'file-input[]') {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgId = event.target.id.replace('file-input', 'previewImg');
                        document.getElementById(imgId).src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('visible');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('visible');
        }

        function validateForm() {
            let isValid = true;
            const fields = ['title', 'content', 'Date', 'StartTime', 'EndTime', 'Fee', 'Host', 'Phone', 'Venue', 'Seat', 'Precautions', 'EventType'];
            
            fields.forEach(field => {
                const input = document.getElementById(field);
                const errorElement = document.getElementById(`${field.toLowerCase()}Error`);
                if (!input.value) {
                    isValid = false;
                    errorElement.textContent = `${field.replace(/([A-Z])/g, ' $1').trim()} is required`;
                } else {
                    errorElement.textContent = '';
                }
            });

            return isValid;
        }

        document.getElementById('eventForm').addEventListener('submit', function(event) {
            event.preventDefault();
            if (validateForm()) {
                showLoading();
                // 在这里可以添加 AJAX 请求来提交表单
                setTimeout(() => {
                    hideLoading();
                    alert('Event created successfully!');
                    this.reset();
                }, 2000); // 模拟 2 秒的提交过程
            }
        });

        

    
    </script>
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

            const imgSrc = member.avatar ;

            card.innerHTML = `
            <img src="${imgSrc}" alt="${member.name}">
            <div class="member-info">
                <h2>${member.name}</h2>
                <p>${member.email}</p>
            </div>
            <a href="member.php?member_id=${member.member_id}" class="view-button">View Profile</a>
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
