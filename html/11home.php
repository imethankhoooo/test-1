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
        

    </div>

    <div class="Page Page1 hidden">
        
        <div class="search-container " id="home-search">
            <input type="test" class="search-input" id="search-input" placeholder=" " required>
            <label class="search-placeholder">Search</label>
        </div>
        <div class="select-container" >
            <div id="img-container">
                <div class="img-item" id="img1" style=" background-image: url('../Img/pexels-shkrabaanthony-5878680.jpg');">
                    <div class="content">
                        <div class="img-title">MEET YOUR PERSONAL TRAINER</div>
                        <div class="img-text">Not sure where to start? Try one-on-one sessions with our personal trainer! Our trainers will work together with you to tailor a training plan that fits you and your goals, monitor your progress and help you move forward with every session.</div>
                        <button  >seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img2" style="background-image: url('../Img/9MqoKp.jpg');">
                    <div class="content">
                        <div class="img-title">Our Amenities</div>
                        <div class="img-text">TOP SPEED AMENITIES.</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img3" style="background-image: url('../Img/19238196_312409769281059_35031026448672256_o.width-1920.jpg');">
                    <div class="content">
                        <div class="img-title">Own A Gym</div>
                        <div class="img-text">Come explore our fully equipped, full-range weight training space.</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img4" style="background-image: url('../Img/stock-photo-sporty-people-exercising-in-gym.jpg');">
                    <div class="content">
                        <div class="img-title">Gallery</div>
                        <div class="img-text"></div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img5" style="background-image: url('..//Img/photo-1534438327276-14e5300c3a48.jpg');">
                    <div class="content">
                        <div class="img-title">Find A Gym</div>
                        <div class="img-text">You’re One Step Closer To Making Healthy Happen
                        Find Your Local Gym And Get Your Free Pass</div>
                        <button>seeMore</button>
                    </div>
                </div>
                <div class="img-item" id="img6" style="background-image: url('../Img/1_5pyrdtIlYVp3ZvwR8C1Yrw.jpg');">
                    <div class="content">
                        <div class="img-title">Employee Wellness</div>
                        <div class="img-text">Invest in the health of your employees and the return is exponential. A healthier, more motivated workforce is a happier, more productive workforce. Through our innovative approach to wellness, we’ll help both you, and your employees, improve your bottom line.</div>
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

    <div class="Page Page2 ">
        <div class="profileImg-Container">
            <img src="../Img/cartoon 1.png" alt="" class="profileImg">
            <h3 class="profileUsername">Matthew Yong</h3>
        </div>
        <div class="profile">
            
            <form action="../php/test.php" method="GET">
                
            </form>
        </div>
    </div>

    <div class="Page Page3 hidden">
        <div class="searchEvent-container">
            <input type="text" class="searchEvent" placeholder="Search....">
        </div>
        <div class="eventCard-Container">
             <div class="eventCard">
            <img class="eventCardImage" src="../Img/6-Pax-Fitness-Centre-JB-2-1024x683.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Johor Bahru</h2>
                <p>Lot 1-015 to Lot1-021 R&F Mall, Tanjung Puteri, 80300 Johor Bahru, Johor</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>
        <div class="eventCard">
            <img class="eventCardImage" src="../Img/533997883.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Bukit Bintang</h2>
                <p>No. 12, Jalan Bukit Bintang,
                    55100 Kuala Lumpur,
                    Wilayah Persekutuan Kuala Lumpur,
                    Malaysia.</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>
        <div class="eventCard">
            <img class="eventCardImage" src="../Img/1000s.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Pandan Perdana</h2>
                <p>1M, Jalan Perdana 4/5, Pandan Perdana, 55300 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>
        <div class="eventCard">
            <img class="eventCardImage" src="../Img/LeanBodies.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Klang</h2>
                <p>51a jalan bendahara 2/ks07, off, Jalan Raja Nong, taman sejati, 41200 Klang, Selangor</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>
        <div class="eventCard">
            <img class="eventCardImage" src="../Img/hotelimage.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Shah Alam</h2>
                <p>2-G & 2-1, Jalan Kemuning Prima C33/C, Kemuning Utama, 40400 Shah Alam, Selangor</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>
        <div class="eventCard">
            <img class="eventCardImage" src="../Img/bWFpbi9pbWFnZXMvZ3ltLTEuanBn.jpg" alt="Gym Image 1">
            <div class="eventCardInfo">
                <h2>Penang</h2>
                <p>59-B, Lebuh Tye Sin, 10300 George Town, Pulau Pinang.</p><br>
                <a href="tel:+1300-123-998">1300-123-998</a><br>
                <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
            </div>
        </div>

        </div>
    </div>

    <div class="Page Page4 hidden">

    </div>

    <script>

    </script>


</body>
<script src="../js/page.js"></script>
<script src="../js/home.js"></script>

</html>