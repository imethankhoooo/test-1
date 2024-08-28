<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/eventPage.css">
    <title>Event</title>
    
</head>

<body>
<?php
// 创建数据库连接
$conn = new mysqli('localhost', 'root', '', 'php-assginment');

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 假设你获取的事件ID是通过GET请求传递的
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 1;

// 从数据库中获取事件详情
$sql = "SELECT event_name, description, location, event_date, start_time,end_time, fee, event_host, phone,  note, banner_image FROM event WHERE event_id =  ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($event) {
    // 将事件详情存储在变量中
    $event_name = $event['event_name'];
    $event_description = $event['description'];
    $event_location = $event['location'];
    $event_date = $event['event_date'];
    $event_StartTime = $event['start_time'];
    $event_EndTime = $event['end_time'];
    $event_fee = $event['fee'];
    $event_host = $event['event_host'];
    $event_phone = $event['phone'];
    $event_note = $event['note'];
    $banner_image = $event['banner_image'];
} else {
    echo "未找到该事件的详细信息。";
}

// 从item表中获取 "Things to Bring" 数据
$sql = "SELECT description, logo FROM item WHERE event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

$things_to_bring = [];

while($row = $result->fetch_assoc()) {
    $things_to_bring[] = $row;
}

$stmt->close();
$conn->close();
?>
<style>
         .hero {
                position: relative;
                height: 100vh;
                background: url('<?php echo htmlspecialchars($banner_image); ?>') no-repeat center center/cover;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-align: center;
            }
    </style>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($event_name); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($event_description)); ?></p>
            <a href="#sign-up">Sign Up Now</a>
        </div>
    </section>

    <!-- Event Details Section -->
    <section class="event-details">
        <h2>Event Details</h2>
        <ul>
            <li><span><i class="fas fa-calendar-alt"></i> Date:</span> <span><?php echo htmlspecialchars($event_date); ?></span></li>
            <li><span><i class="fas fa-clock"></i> Time:</span> <span><?php echo htmlspecialchars($event_StartTime); ?> - <?php echo htmlspecialchars($event_EndTime); ?></span></li>
            <li><span><i class="fas fa-map-marker-alt"></i> Venue:</span> <span><?php echo nl2br(htmlspecialchars($event_location)); ?></span></li>
            <li><span><i class="fas fa-money-bill-wave"></i> Fee:</span> <span><?php echo htmlspecialchars($event_fee); ?></span></li>
            <li><span><i class="fas fa-user"></i> Event Host:</span> <span><?php echo htmlspecialchars($event_host); ?></span></li>
            <li><span><i class="fas fa-exclamation-circle"></i> Precautions:</span> <span><?php echo nl2br(htmlspecialchars($event_note)); ?></span></li>
        </ul>
    </section>

    <!-- Things to Bring Section -->
    <section class="things-to-bring">
        <h3>Things to Bring</h3>
        <div class="items">
        <?php if (!empty($things_to_bring)) { ?>
            
            <?php foreach ($things_to_bring as $thing) { ?>
            <div class="item">
            <?php if (!empty($thing['logo'])) { ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($thing['logo']); ?>" alt="Item Image">
                <?php } ?>
                <p><?php echo htmlspecialchars($thing['description']); ?></p>
            </div>
            <?php } ?>
            <?php } else { ?>
            <p>No items to bring.</p>
        <?php } ?>
            
        </div>
    </section>

    <!-- More Events Section -->
    <section class="more-events">
        <h2>Other Events</h2>
        <div class="event-carousel">
            <div class="carousel-controls">
                <button onclick="prevImage()">&lt;</button>
                <button onclick="nextImage()">&gt;</button>
            </div>
            <div class="carousel-images">
                <img src="../Img/191003-malepersonaltrainer-stock.jpg" alt="Event 1">
                <img src="../Img/191003-malepersonaltrainer-stock.jpg" alt="Event 2">
                <img src="../Img/191003-malepersonaltrainer-stock.jpg" alt="Event 3">
            </div>
        </div>
    </section>

    <script>
        const images = document.querySelector('.carousel-images');
        const totalImages = images.querySelectorAll('img').length;
        let index = 0;

        function showImage(i) {
            images.style.transform = `translateX(${-i * 100}%)`;
        }

        function prevImage() {
            index = (index > 0) ? index - 1 : totalImages - 1;
            showImage(index);
        }

        function nextImage() {
            index = (index < totalImages - 1) ? index + 1 : 0;
            showImage(index);
        }
    </script>
</body>

</html>
