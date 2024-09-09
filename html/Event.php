<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event_name); ?></title>
    <link rel="stylesheet" href="../css/eventPage.css">
</head>
<body>
    <?php

    session_start();
    function isAdmin() {
 
        if (!isset($_SESSION['admin_id'])) {
            return false;
        }
    
       
        global $conn;
        if (!$conn) {
            $conn = new mysqli('localhost', 'root', '', 'php-assginment');
            if ($conn->connect_error) {
                die("连接失败: " . $conn->connect_error);
            }
        }
    
        $sql = "SELECT admin_id FROM admin_member WHERE admin_id = ?";
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
    $conn = new mysqli('localhost', 'root', '', 'php-assginment');

 
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    
    $event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 1;

 
    $sql = "SELECT event_name, description, location, event_date, start_time, end_time, fee, event_host, phone, note, banner_image, seat FROM event WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if ($event) {
   
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
        $available_seats = $event['seat'];
    } else {
        echo "No Event Information Found";
    }


    $sql = "SELECT description, logo FROM item WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $things_to_bring = [];

    while($row = $result->fetch_assoc()) {
        $things_to_bring[] = $row;
    }
    ?>

    <style>
        .hero {
            background: url('<?php echo htmlspecialchars($banner_image); ?>') no-repeat center center/cover;
        }
    </style>

    <!-- Hero Section -->
    <header class="header">
        <nav class="navbar">
            <a href="home.php" class="logo">First Fitness</a>
            <ul class="nav-menu">
                <li><a href="#about">About</a></li>
                <li><a href="#details">Details</a></li>
                <li><a href="#bring">What to Bring</a></li>
                <li><a href="#more-events">More Events</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="hero" class="hero" style="background-image: url('<?php echo htmlspecialchars($banner_image); ?>');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1><?php echo htmlspecialchars($event_name); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($event_description)); ?></p>
                <a class="cta-button" href="booking.php?event_id=<?php echo $event_id; ?>" class="cta-button">Book Now</a>
            </div>
        </section>

        <section id="details" class="event-details">
            <h2>Event Details</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Date</h3>
                    <p><?php echo htmlspecialchars($event_date); ?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <h3>Time</h3>
                    <p><?php echo htmlspecialchars($event_StartTime); ?> - <?php echo htmlspecialchars($event_EndTime); ?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Venue</h3>
                    <p><?php echo nl2br(htmlspecialchars($event_location)); ?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>Fee</h3>
                    <p><?php echo htmlspecialchars($event_fee); ?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-user"></i>
                    <h3>Event Host</h3>
                    <p><?php echo htmlspecialchars($event_host); ?></p>
                </div>
                <div class="detail-item">
                    <i class="fas fa-chair"></i>
                    <h3>Available Seats</h3>
                    <p><?php echo htmlspecialchars($available_seats); ?></p>
                </div>
            </div>
            <div class="event-note">
                <h3><i class="fas fa-exclamation-circle"></i> Precautions</h3>
                <p><?php echo nl2br(htmlspecialchars($event_note)); ?></p>
            </div>
        </section>

        <section id="bring" class="things-to-bring">
            <h2>Things to Bring</h2>
            <div class="items">
            <?php if (!empty($things_to_bring)) { ?>
                <?php foreach ($things_to_bring as $thing) { ?>
                <div class="item">
                <?php if (!empty($thing['logo'])) { ?>
                    <img src="<?php echo htmlspecialchars($thing['logo']); ?>" alt="Item Image">
                <?php } ?>
                    <p><?php echo htmlspecialchars($thing['description']); ?></p>
                </div>
                <?php } ?>
            <?php } else { ?>
                <p>No items to bring.</p>
            <?php } ?>
            </div>
        </section>
        <?php if (isAdmin()): ?>
        <div class="admin-buttons">
            <a href="edit_event.php?event_id=<?php echo $event_id; ?>" class="admin-button">Edit Event</a>
            <button onclick="confirmDelete()" class="admin-button delete">Delete Event</button>
        </div>
        <?php endif; ?>
        <section id="more-events" class="more-events">
            <h2>Discover More Events</h2>
            <div class="event-carousel">
                <div class="carousel-controls">
                    <button onclick="prevImage()" class="carousel-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <button onclick="nextImage()" class="carousel-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="carousel-images">
                    <?php
                    // Fetch up to 5 events from the database
                    $query = "SELECT event_id, event_name, banner_image, event_date FROM event ORDER BY event_date DESC LIMIT 5";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $events = $stmt->get_result();

                    foreach ($events as $event): ?>
                        <div class="carousel-item">
                            <img src="<?php echo htmlspecialchars($event['banner_image']); ?>" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                            <div class="carousel-caption">
                                <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                                <p><?php echo htmlspecialchars($event['event_date']); ?></p>
                                <a href="event.php?event_id=<?php echo $event['event_id']; ?>" class="btn-view-more">View More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        
    </main>

    <footer class="footer">
        <p>&copy; 2024 EventMaster. All rights reserved.</p>
    </footer>
    <script>
        const carousel = document.querySelector('.carousel-images');
        const items = carousel.querySelectorAll('.carousel-item');
        const totalItems = items.length;
        let currentIndex = 0;

        function showImage(index) {
            carousel.style.transform = `translateX(${-index * 100}%)`;
        }

        function prevImage() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
            showImage(currentIndex);
        }

        function nextImage() {
         currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
            showImage(currentIndex);
        }


        setInterval(nextImage, 5000);
        function confirmDelete() {
            if (confirm("Are you sure you want to delete this event? This action cannot be undone.")) {

                window.location.href = "delete_event.php?event_id=<?php echo $event_id; ?>";
            }
        }
    </script>
</body>
</html>