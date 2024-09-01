<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/addcustomer.css">
    <link rel="stylesheet" href="../css/page3.css">
    <link rel="stylesheet" href="../css/profile.css">

    <title>保存用户数据</title>

</head>

<body>
<?php
if(isset($_SESSION['member_id'])){
    $member_id =$_SESSION['member_id'];
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
                <li class="list" id="login" style="--clr:black;">
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
                <li class="list" style="--clr:blue;">
                    <a onclick="switchPage('Page4')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133929.png"
                                alt=""></span>
                        <span class="text">Add</span>
                    </a>
                </li>
                <li class="list" style="--clr:grey;">
                    <a href="about.html">
                        <span class="icon"><img class="imgIcon" src="../Img/view-details.png"
                                alt=""></span>
                        <span class="text">View</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="top">


    </div>

    <div class="Page Page1 ">

        <div class="search-container " id="home-search">
            <input type="test" class="search-input" id="search-input" placeholder=" " required>
            <label class="search-placeholder">Search</label>
        </div>
        <div class="select-container">
            <div id="img-container">
                <div class="img-item" id="img1"
                    style=" background-image: url('../Img/pexels-shkrabaanthony-5878680.jpg');">
                    <div class="content">
                        <div class="img-title">MEET YOUR PERSONAL TRAINER</div>
                        <div class="img-text">Not sure where to start? Try one-on-one sessions with our personal
                            trainer! Our trainers will work together with you to tailor a training plan that fits you
                            and your goals, monitor your progress and help you move forward with every session.</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img2"
                    style="background-image: url('../Img/9MqoKp.jpg');">
                    <div class="content">
                        <div class="img-title">Our Amenities</div>
                        <div class="img-text">TOP SPEED AMENITIES.</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img3"
                    style="background-image: url('../Img/19238196_312409769281059_35031026448672256_o.width-1920.jpg');">
                    <div class="content">
                        <div class="img-title">Own A Gym</div>
                        <div class="img-text">Come explore our fully equipped, full-range weight training space.</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img4"
                    style="background-image: url('../Img/stock-photo-sporty-people-exercising-in-gym.jpg');">
                    <div class="content">
                        <div class="img-title">Gallery</div>
                        <div class="img-text"></div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img5"
                    style="background-image: url('..//Img/photo-1534438327276-14e5300c3a48.jpg');">
                    <div class="content">
                        <div class="img-title">Find A Gym</div>
                        <div class="img-text">You’re One Step Closer To Making Healthy Happen
                            Find Your Local Gym And Get Your Free Pass</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img6"
                    style="background-image: url('../Img/1_5pyrdtIlYVp3ZvwR8C1Yrw.jpg');">
                    <div class="content">
                        <div class="img-title">Employee Wellness</div>
                        <div class="img-text">Invest in the health of your employees and the return is exponential. A
                            healthier, more motivated workforce is a happier, more productive workforce. Through our
                            innovative approach to wellness, we’ll help both you, and your employees, improve your
                            bottom line.</div>
                        <button>seeMore</button>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <div class="s-button ">&lt;</div>
                <div class="s-button">&gt;</div>
            </div>
        </div>
    </div>

    <div class="Page Page2 hidden">
        <?php
        session_start();
        $conn = new mysqli('localhost', 'root', '', 'php-assginment');
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        if (isset($_SESSION['member_id'])) {
            $member_id = $_SESSION['member_id'];
            
        }        
        ?>
    <div class="profileAside">
    <div class="profileImg-Container">
        <img src="<?php echo htmlspecialchars($avatar_path); ?>" alt="Profile Image" class="profileImg">
        <h3 class="profileUsername"><?php echo htmlspecialchars($membername); ?></h3>
    </div>
</div>
<div class="profileInformation">
    <div class="infoSection">
        <div class="profileCard">
            <h2>Personal Information</h2>
            <div class="infoGroup">
                <p><strong>Name:</strong> <span class="profileInformation"><?php echo htmlspecialchars($name); ?></span></p>
                <p><strong>Email:</strong> <span class="profileInformation"><?php echo htmlspecialchars($_SESSION['email']); ?></span></p>
                <p><strong>Phone:</strong> <span class="profileInformation"><?php echo htmlspecialchars($phone); ?></span></p>
            </div>
        </div>
    </div>
    <div class="infoSection">
        <div class="profileCard">
            <h2>Address</h2>
            <p><strong>Address:</strong> <span class="profileInformation"><?php echo htmlspecialchars($address); ?></span></p>
        </div>
    </div>
    <div class="infoSection">
        <div class="profileCard">
            <h2>Bio</h2>
            <p><span class="profileInformation"><?php echo htmlspecialchars($bio); ?></span></p>
        </div>
    </div>
    <div class="infoSection">
        <div class="profileCard">
            <h2>Social Media</h2>
            <p><strong>LinkedIn:</strong> <a href="<?php echo htmlspecialchars($socialmedia); ?>" class="profileLink"><?php echo htmlspecialchars($socialmedia); ?></a></p>
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
    </div>
    

    <div class="Page Page3 hidden">
        <div class="searchEvent-container">
        <input type="text" id="searchInput"  class="searchEvent" placeholder="Search events...">

        </div>
        <div class="eventCard-Container">
        <?php
// 1. 连接数据库


$conn = new mysqli('localhost', 'root', '', 'php-assginment');

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 查询数据
$sql = "SELECT event_id, banner_image, event_name, location FROM event";
$result = $conn->query($sql);

// 检查查询是否成功
if ($result === false) {
    die("查询失败: " . $conn->error);
}

// 检查是否有结果
if ($result->num_rows > 0) {
    // 输出每一行数据
    while ($row = $result->fetch_assoc()) {
        $event_id = $row['event_id'];  // 获取事件ID
        echo '<div class="eventCard">';
        
        // 处理 banner_image
        if (is_resource($row['banner_image'])) {
            $imgSrc = 'data:image/jpeg;base64,' . base64_encode($row['banner_image']);
        } else {
            $imgSrc = htmlspecialchars($row['banner_image']);
        }

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

    <div class="Page Page4 hidden">

    </div>

    <script>

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
        eventCardContainer.innerHTML = ''; // 清空容器
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
        }, 300); // 300ms 防抖
    });

    // 初始加载所有事件
    fetchEvents('');
});
    </script>


</body>
<script src="../js/page.js"></script>
<script src="../js/home.js"></script>

</html>