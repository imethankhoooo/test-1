<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/page.css">
    <link rel="stylesheet" href="../css/facility.css">

    <title>保存用户数据</title>
 
</head>

<body>
<aside>
        <div class="navigation">
            <div class="menuToggle"></div>
            <ul>
                <li class="list active" style="--clr:red;">
                    <a onclick="switchPage('Page1')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 131631.png">
                        </span>
                        <span class="text">Home</span>
                    </a>
                </li>
                <li class="list" id="login" style="--clr:purple;">
                    <a onclick="switchPage('Page2')">
                        <span class="icon"><img class="imgIcon" src="../Img/Screenshot 2024-06-08 133129.png"></span>
                        <span class="text">Login</span>
                    </a>
                </li>
                <li class="list" style="--clr:black;">
                    <a onclick="switchPage('Page3')">
                        <span class="icon"><img class="imgIcon" src="../Img/free-location-icon-2952-thumbb.png"
                                alt=""></span>
                        <span class="text">Location</span>
                    </a>
                </li>
                <li class="list" style="--clr:orange;">
                    <a onclick="switchPage('Page4')">
                        <span class="icon"><img class="imgIcon" src="../Img/clipart1518456.png"
                                alt=""></span>
                        <span class="text">Facility</span>
                    </a>
                </li>
                <li class="list" style="--clr:grey;">
                    <a onclick="switchPage('Page5')">
                        <span class="icon"><img class="imgIcon" src="../Img/user_icon_007-removebg-preview.png"
                                alt=""></span>
                        <span class="text">Plan</span>
                    </a>
                </li>
                <li class="list" style="--clr:green;">
                    <a onclick="switchPage('Page6')">
                        <span class="icon"><img class="imgIcon" src="../Img/booking.png"
                                alt=""></span>
                        <span class="text">Booking</span>
                    </a>
                </li>
                
            </ul>
        </div>
    </aside>

<div class="top">
        <!-- Top bar content, e.g., logo, contact information -->
    </div>

    <div class="content">
        <div class="Page Page6 ">
            <h1>Our Facilities</h1>

            <!-- Introduction section -->
            <div class="facility-intro">
                <p>Welcome to our state-of-the-art facilities! Whether you’re looking for a high-energy workout, a relaxing space to unwind, or amenities to support your wellness journey, we’ve got you covered.</p>
            </div>

            <!-- Facilities list -->
            <div class="facility-list">
                <!-- Gym Area -->
                <div class="facility-item">
                    <h2>Gym Area</h2>
                    <img src="../Img/Gym_Cardio_Area_Overlooking_Greenery.jpg" alt="Gym Area" class="facility-img">
                    <p>Our gym area is equipped with the latest machines and free weights to help you achieve your fitness goals. From treadmills to squat racks, we’ve got everything you need.</p>
                </div>

                <!-- Swimming Pool -->
                <div class="facility-item">
                    <h2>Swimming Pool</h2>
                    <img src="../Img/Backyardpool.jpg" alt="Swimming Pool" class="facility-img">
                    <p>Our 25-meter pool is perfect for laps or a relaxing swim. We also offer aqua aerobics classes for all levels.</p>
                </div>

                <!-- Sauna and Steam Room -->
                <div class="facility-item">
                    <h2>Sauna & Steam Room</h2>
                    <img src="../Img/5bc64f6ad5318a004b87392d_whitney-peak-hotel-sauna-steam-room-amenities-basecamp-climbing-gym-1.jpg" alt="Sauna" class="facility-img">
                    <p>Unwind in our luxurious sauna and steam rooms, perfect for post-workout relaxation and detoxification.</p>
                </div>

                <!-- Yoga Studio -->
                <div class="facility-item">
                    <h2>Yoga Studio</h2>
                    <img src="../Img/Victoria+Web-29.jpg" alt="Yoga Studio" class="facility-img">
                    <p>Our serene yoga studio offers a variety of classes, from beginner to advanced, designed to enhance your mind-body connection.</p>
                </div>

                <!-- Personal Training -->
                <div class="facility-item">
                    <h2>Personal Training</h2>
                    <img src="../Img/191003-malepersonaltrainer-stock.jpg" alt="Personal Training" class="facility-img">
                    <p>Work with our expert personal trainers to create a customized fitness plan tailored to your specific needs and goals.</p>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    document.querySelectorAll('.facility-item').forEach(item => {
        item.addEventListener('mouseover', () => {
            item.querySelector('.facility-text').style.opacity = '1';
            item.querySelector('.facility-img').style.transform = 'scale(1.1)';
        });
        item.addEventListener('mouseout', () => {
            item.querySelector('.facility-text').style.opacity = '0';
            item.querySelector('.facility-img').style.transform = 'scale(1)';
        });
    });

</script>

</html>