<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/addcustomer.css">
    <link rel="stylesheet" href="../css/page3.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/booking_information.css">
    <link rel="stylesheet" href="../css/color.css">

    <title>home Page</title>

</head>

<header class="header">
    <h1 class="logo-text">First Fitness</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<body>
    <?php
    session_start();

    if (isset($_SESSION['member_id'])) {
        $member_id = $_SESSION['member_id'];
    }
    ?>
    <aside>
        <div class="navigation">
            <div class="menuToggle"></div>
            <ul gg>
                <li class="list active" style="--clr:red;">
                    <a onclick="switchPage('Page1')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 131631.png">
                        </span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list" id="login" style="--clr:yellow;">
                    <a onclick="switchPage('Page2')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133129.png"></span>
                        <span class="text">Login</span>
                    </a>
                </li>
                <li class="list" style="--clr:green;">
                    <a onclick="switchPage('Page3')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133525.png"
                                alt=""></span>
                        <span class="text">Search</span>
                    </a>
                </li>
                <li class="list" style="--clr:grey;">
                    <a onclick="switchPage('Page5')">
                        <span class="icon"><img class="imgIcon" src="../Img/view-details.png"
                                alt=""></span>
                        <span class="text">View Booking</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="top">


    </div>

    <div class="Page Page1 ">
        <?php


        $conn = new mysqli('localhost', 'root', '', 'php-assignment');


        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM event ORDER BY event_date";
        $result = $conn->query($sql);

        $events = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }

        $conn->close();
        ?>
        <div class="search-container" id="home-search">
            <input type="text" class="search-input" id="search-input" placeholder=" " required>
            <label class="search-placeholder">Search</label>
        </div>
        <div class="select-container">
            <div id="img-container">
                <?php foreach ($events as $event): ?>
                    <div class="img-item" style="background-image: url('<?php echo htmlspecialchars($event['banner_image']); ?>');">
                        <div class="content">
                            <div class="img-title"><?php echo htmlspecialchars($event['event_name']); ?></div>
                            <div class="img-text"><?php echo htmlspecialchars($event['description']); ?></div>
                            <button onclick="window.location.href='Event.php?event_id=<?php echo $event['event_id']; ?>'">See More</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="button-container">
                <div class="s-button">&lt;</div>
                <div class="s-button">&gt;</div>
            </div>
        </div>
    </div>

    <div class="Page Page2 hidden">
        <?php

        $conn = new mysqli('localhost', 'root', '', 'php-assignment');


        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        if (isset($_SESSION['member_id'])) {
            $member_id = $_SESSION['member_id'];


            $sql = "SELECT avatar, name, username, gender, phone, address, bio, experience, socialMedia FROM member WHERE member_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {

                echo "<script>alert('No matching member found. Please log in.'); window.location.href='login.php';</script>";
                exit;
            } else {

                $member = $result->fetch_assoc();
                $avatar_path = $member['avatar'] ? $member['avatar'] : '../Img/cartoon 1.png';
                $membername = $member['username'];
                $name = $member['name'];
                $gender = $member['gender'];
                $phone = $member['phone'];
                $address = $member['address'];
                $bio = $member['bio'];
                $experience = $member['experience'];
                $socialMedia = $member['socialMedia'];
            }
        } else {

            echo "<script>alert('No session found. Please log in.'); window.location.href='login.php';</script>";
            exit;
        }
        ?>
        <div class="Page2">
            <!-- First Section -->
            <div class="FirstSection">
                <div class="profileAside">
                    <div class="profileImg-Container">
                        <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Profile Image" class="profileImg">
                        <h3 class="profileUsername"><?php echo htmlspecialchars($membername); ?></h3>
                    </div>
                </div>
                <div class="profileInformation">
                    <div class="infoSection">
                        <div class="profileCard1">
                            <h2>Personal Information</h2>
                            <div class="infoGroup">
                                <p><strong>Name:</strong> <span class="profileInformation"><?php echo htmlspecialchars($name); ?></span></p>
                                <p><strong>Email:</strong> <span class="profileInformation"><?php echo htmlspecialchars($_SESSION['email']); ?></span></p>
                                <p><strong>Phone:</strong> <span class="profileInformation"><?php echo htmlspecialchars($phone); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Section -->
            <div class="SecondSection">
                <div class="tabs">
                    <ul>
                        <li class="tab timeline active">
                            <i class="ri-eye-fill ri"></i>
                            <span>Timeline</span>
                        </li>
                        <li class="tab about">
                            <i class="ri-user-3-fill ri"></i>
                            <span>About</span>
                        </li>
                    </ul>
                </div>

                <!-- Timeline Section -->
                <div class="timelineSection">
                    <div class="infoSection">
                        <div class="profileCard">
                            <h2>Social Media</h2>
                            <p><a href="<?php echo htmlspecialchars($socialMedia); ?>" class="profileLink"><?php echo htmlspecialchars($socialMedia); ?></a></p>
                        </div>
                    </div>
                    <div class="infoSection">
                        <div class="profileCard">
                            <h2>Experience</h2>
                            <ul class="profileList">
                                <li><?php echo htmlspecialchars($experience); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="aboutSection">
                    <div class="infoSection">
                        <div class="profileCard">
                            <h2>Address</h2>
                            <p><span class="profileInformation"><?php echo htmlspecialchars($address); ?></span></p>
                        </div>
                    </div>
                    <div class="infoSection">
                        <div class="profileCard">
                            <h2>Bio</h2>
                            <p><span class="profileInformation"><?php echo htmlspecialchars($bio); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="editButtonContainer">
                    <a href="edit_memberInformation.php" class="editButton">Edit</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>


    <div class="Page Page3 hidden">
        <header class="searchEvent-header">
            <h1>Discover Exciting Events</h1>
        </header>

        <div class="searchEvent-container">
            <aside class="search-section">
                <input type="text" id="searchInput" class="search-input" placeholder="Search for events...">
                <div class="filter-section">
                    <h3>Filters</h3>
                    <div class="filter-option">
                        <input type="radio" id="filterNone" name="filter" value="none" checked>
                        <label for="filterNone">No Filter</label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="filterRecent" name="filter" value="recent">
                        <label for="filterRecent">Most Recent</label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="filterPopular" name="filter" value="popular">
                        <label for="filterPopular">Most Popular</label>
                    </div>
                    <div class="filter-option">
                        <input type="radio" id="filterPrice" name="filter" value="price_low">
                        <label for="filterPrice">Lowest Price</label>
                    </div>
                </div>
            </aside>

            <main class="event-section">
                <div class="event-grid">
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'php-assignment');
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT event_id, banner_image, event_name, location FROM event";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="event-card">';
                            echo '<img class="event-image" src="' . htmlspecialchars($row['banner_image']) . '" alt="Event Image">';
                            echo '<div class="event-info">';
                            echo '<h2 class="event-name">' . htmlspecialchars($row['event_name']) . '</h2>';
                            echo '<p class="event-location">' . htmlspecialchars($row['location']) . '</p>';
                            echo '<a href="Event.php?event_id=' . htmlspecialchars($row['event_id']) . '" class="event-button">Learn More</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="no-results">No events found</p>';
                    }
                    $conn->close();
                    ?>
                </div>
        </div>
    </div>

    <div class="Page Page4 hidden">

    </div>
    <div class="Page Page5 hidden">

        <div class="Booking-container">
            <?php


            $conn = new mysqli("localhost", "root", "", "php-assignment");


            if ($conn->connect_error) {
                die("<p class='error'>Connect error: " . $conn->connect_error . "</p>");
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_ticket'])) {
                $ticket_id = intval($_POST['delete_ticket']);
                $stmt = $conn->prepare("DELETE FROM ticket WHERE ticket_id = ?");
                $stmt->bind_param("i", $ticket_id);
                if ($stmt->execute()) {
                    echo "<p style='color: green;'>Delete Ticket Successful</p>";
                } else {
                    echo "<p style='color: red;'>Delete Ticket Fail: " . $stmt->error . "</p>";
                }
                $stmt->close();
            }

            $member_id = $_SESSION['member_id'];

            if (isset($member_id)) {

                echo "<h2>Payment History</h2>";
                echo "<table>";
                echo "<thead><tr><th>Invoice ID</th><th>Event ID</th><th>Payment amount</th><th>Booking Date</th></tr></thead>";
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
                        echo "<td>RM" . number_format($row["paymentAmount"], 2) . "</td>";
                        echo "<td>" . $row["booking_date"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>No Payment History Found</td></tr>";
                }

                echo "</tbody></table>";


                echo "<h2>Current Ticket information</h2>";

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
                        echo "<div>Date: " . $row['event_date'] . "</div>";
                        echo "<div>Ticket ID: " . $row['ticket_id'] . "</div>";
                        echo "<div>Invoice ID: " . $row['booking_id'] . "</div>";
                        echo "<div>Purchase date: " . $row['issue_date'] . "</div>";
                        echo "</div>";
                        echo "<button class='delete-btn' onclick='deleteTicket(" . $row['ticket_id'] . ")'>Delete</button>";
                        echo "</div>";
                    }
                } else {
                    echo "<p style='text-align:center;'>No ticket information found</p>";
                }
            }


            $conn->close();

            ?>
        </div>
        <footer>
    <div class="footer-container">
        <div class="nav-section">
            <h2>Quick Link</h2>
        </div>
        <div class="footer-col">
            <ul>
                <hr>
                <li><a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">First Vacation</a></li>
                <li><a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">Second Vacation</a></li>
            </ul>
        </div>
        <form class="subcribe-form">
            <input type="text" placeholder="Your Email Address" />
            <a href="" class="ctn">Subscribe Us</a>
        </form>
        <div class="social">
            <a href="https://www.facebook.com/" target="_blank"><img src="../Img/facebook_icon.png" alt="facebook" /></a>
            <a href="https://www.instagram.com/" target="_blank"><img src="../Img/instagram_icon.png" alt="instagram" /></a>
            <a href="https://www.youtube.com/" target="_blank"><img src="../Img/youtube_icon.png" alt="youtube" /></a>
            <a href="https://twitter.com/" target="_blank"><img src="../Img/twitter_icon.png" alt="twitter" /></a>
        </div>
        <div class="copyright">&copy; 2024 My Gym. All rights reserved.</div>
    </div>
</footer>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.SecondSection .tabs ul .tab');
            const timelineSection = document.querySelector('.timelineSection');
            const aboutSection = document.querySelector('.aboutSection');

            function showSection(sectionToShow) {
                timelineSection.style.display = 'none';
                aboutSection.style.display = 'none';
                sectionToShow.style.display = 'block';
            }

            function handleTabClick(clickedTab) {
                tabs.forEach(tab => tab.classList.remove('active'));
                clickedTab.classList.add('active');

                if (clickedTab.classList.contains('timeline')) {
                    showSection(timelineSection);
                } else if (clickedTab.classList.contains('about')) {
                    showSection(aboutSection);
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    handleTabClick(this);
                });
            });

            // Show the initially active section
            const initialActiveTab = document.querySelector('.SecondSection .tabs ul .tab.active');
            if (initialActiveTab) {
                handleTabClick(initialActiveTab);
            } else if (tabs.length > 0) {
                // If no tab is initially active, activate the first tab
                handleTabClick(tabs[0]);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('footer');
            const page5 = document.querySelector('.Page5');

            // Initially hide the footer
            footer.style.display = 'none';

            function checkFooterVisibility() {
                // Check if Page5 is currently visible
                if (page5.classList.contains('hidden')) {
                    footer.style.display = 'none';
                    return;
                }

                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollHeight = page5.scrollHeight;
                const clientHeight = document.documentElement.clientHeight;

                // Check if the user has scrolled to the bottom of Page5
                if (scrollTop + clientHeight >= scrollHeight - 10) {
                    footer.style.display = 'block'; // Show footer
                } else {
                    footer.style.display = 'none'; // Hide footer
                }
            }

            // Add scroll event listener
            window.addEventListener('scroll', checkFooterVisibility);

            // Add event listener for page switches
            document.addEventListener('pageSwitch', checkFooterVisibility);

            // Initial check
            checkFooterVisibility();
        });
    </script>


</body>
<script src="../js/page.js"></script>
<script src="../js/home.js"></script>
<script src="../js/delete_ticket.js"></script>
<script src="../js/search_event.js"></script>

</html>