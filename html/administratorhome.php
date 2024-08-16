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
    $conn= new mysqli('localhost','root','','php-assginment');
    if($conn->connect_error){
        die('Connection failed: '.$conn->connect_error);
    }
    if($_SERVER['REQUEST_METHOD']=='POST'&& $_POST['submit']=='Add'){
        $errors = [];

        // 获取表单数据
        $eventName = $_POST['title'];
        $eventDescription = $_POST['content'];
        $eventVenue = $_POST['Venue'];
        $eventDate = $_POST['Date'];
        $eventTime = $_POST['Time'];
        $eventFee = $_POST['Fee'];
        $eventHost = $_POST['Host'];
        $eventPrecautions = $_POST['Precautions'];
    
        // 检查文件上传
        $imageData = NULL;
        if (isset($_FILES['eventImgInput']) && $_FILES['eventImgInput']['error'] == UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['eventImgInput']['tmp_name'];
            $imageData = addslashes(file_get_contents($imageTmpName));
        }
    
        // 检查 thingContent
        $thingContents = $_POST['thingContent'];
        $thingImages = $_FILES['file-input'];
        
        $items = [];
        foreach ($thingContents as $index => $thingContent) {
            $imageContent = $thingImages['tmp_name'][$index];
            
            if (empty($thingContent) || empty($imageContent)) {
                // Exit loop without error if any thingContent or image is empty
                break;
            }
    
            // Add content and image to items array
            $imageData = addslashes(file_get_contents($imageContent));
            $items[] = ['text' => $thingContent, 'image' => $imageData];
        }
    
        if (empty($errors)) {
            // 准备 SQL 语句
            $sql = "INSERT INTO event (event_name, event_date, location, description, banner_image, note) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    
            // 使用预处理语句防止 SQL 注入
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
    
            $stmt->bind_param("ssssss", $eventName, $eventDate, $eventVenue, $eventDescription, $imageData, $eventPrecautions);
    
            // 执行语句
            if ($stmt->execute()) {
                $eventId = $stmt->insert_id;
                // Insert items related to the event
                $itemSql = "INSERT INTO items (event_id, item_text, item_image) VALUES (?, ?, ?)";
                $itemStmt = $conn->prepare($itemSql);
                if ($itemStmt === false) {
                    die("Prepare failed: " . $conn->error);
                }
                foreach ($items as $item) {
                    $itemStmt->bind_param("iss", $eventId, $item['text'], $item['image']);
                    $itemStmt->execute();
                }
                $itemStmt->close();
                echo "Event and items added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
    
            // 关闭语句
            $stmt->close();
        } else {
            // 显示错误信息
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
        }
    
        // 关闭连接
        $conn->close();
    }

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

    <div class="Page adminPage1 "></div>
    <div class="Page adminPage2 hidden"></div>
    <div class="Page adminPage3 hidden">
        
    </div>
    <div class="Page adminPage4 hidden">
        <div class="searchEvent-container">
            <input type="text" class="searchEvent" placeholder="Search....">
        </div>
        <div class="eventCard-Container">
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>
            <div class="eventCard">
                <img class="eventCardImage" src="hs_gym_featured_1.jpg" alt="Gym Image 1">
                <div class="eventCardInfo">
                    <h2>Jalan Bukit Bintang</h2>
                    <p>No. 12, Jalan Bukit Bintang,
                        55100 Kuala Lumpur,
                        Wilayah Persekutuan Kuala Lumpur,
                        Malaysia.</p><br>
                    <a href="tel:+1300-123-998">1300-123-998</a><br>
                    <a href="https://www.google.com/maps/dir/3.2362824,101.6918495//@3.2207987,101.7115947,13.1z?entry=ttu">View More</a>
                </div>
            </div>

        </div>
    </div>
    <div class="Page adminPage5 hidden">
        <form action="<?php echo $_SERVER['PHP_SELF']?>">

            <div class="eventImgCointainer" onclick="triggerFileInput('eventImgInput')">
                <input type="file" id="eventImgInput" class="eventImgInput" accept="image/*" style="display:none;" />
                <img class="plus-sign" src="../Img/Screenshot 2024-06-08 133929.png">
            </div>
            <div class="contentBlock">
                <h3 class="eventType">Special Event</h3>
                <input class="title eventInformation" name="title" placeholder="Title:">
                <textarea class="content eventInformation" name="content" rows="4" cols="50"></textarea>
                <span class="line"></span>
                <table class="inputTable">
                    <tr>
                        <td id="qqq"><label class="eventInformation" for="Venue">Venue :</label></td>
                        <td><input name="Venue"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Date">Date :</label></td>
                        <td><input name="Date" type="date"></td>
                    </tr>
                    <tr>
                        <td><label class="eventInformation" for="Time">Time :</label></td>
                        <td><input name="Time" type="time"></td>
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
                        <td><label class="eventInformation">Precautions : </label></td>
                        <td><textarea class="" name="Precautions" cols="30" rows="3"></textarea></td>
                    </tr>
                </table>
                <span class="line"></span>
                <h3 class="thingToBring">Thing to bring</h3>
                <div class="think-Container">
                    <div class="thingIcon">
                        <input type="file" id="file-input0" class="file-input" accept="image/*" style="display:none;" />
                        <div class="img-box" id="img-box0" onclick="triggerFileInput('file-input0')">
                            <img src="../Img/Screenshot 2024-06-08 133929.png" alt="addImg" class="plus-sign"
                                id="plus-sign0">
                        </div>
                        <input name="thingContent" class="thingContent">
                    </div>
                </div>
                <div class="button-container">
                    <input class="button" type="submit" name="submit" value="Add">
                    <input class="button" type="reset" name="reset" value="Reset">
                </div>
            </div>
    </div>

</body>
<script src="../js/page.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
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
    let i = 0;
    let changeImg = 0;
    document.querySelector('.think-Container').addEventListener('change', function (event) {
        let target = event.target;
        var imgBoxId = 'addImg' + target.id.replace('file-input', '');
        var imgBoxElement = document.getElementById(imgBoxId);
        if (imgBoxElement) {
            imgBoxElement.parentNode.removeChild(imgBoxElement);
            changeImg++;
        }
        if (target.classList.contains('file-input')) {
            const file = target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgBoxId = 'img-box' + target.id.replace('file-input', '');
                    const imgBox = document.getElementById(imgBoxId);
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'addImg';
                    img.id = 'addImg' + i;
                    var plusSignId = 'plus-sign' + target.id.replace('file-input', '');
                    const plusSign = document.getElementById(plusSignId);
                    plusSign.classList.add('hidden');
                    imgBox.appendChild(img);
                    if (changeImg == 0) {
                        createNewThingIcon();

                    } else { changeImg-- };
                }
                reader.readAsDataURL(file);
            }
        }

    })

    document.querySelector('.eventImgCointainer').addEventListener('change', function (event) {
        let target = event.target;
        
        const file = target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
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
        newFileInput.className = 'file-input';
        newFileInput.accept = 'image/*';
        newFileInput.style.display = 'none';

        const newImgBox = document.createElement('div');
        newImgBox.className = 'img-box';
        newImgBox.id = 'img-box' + i;
        newImgBox.onclick = function () { triggerFileInput('file-input' + i); };

        const newPlusSign = document.createElement('img');
        newPlusSign.src = '../Img/Screenshot 2024-06-08 133929.png';
        newPlusSign.alt = 'addImg';
        newPlusSign.className = 'plus-sign';
        newPlusSign.id = 'plus-sign' + i;

        const newThingContent = document.createElement('input');
        newThingContent.name = 'thingContent';
        newThingContent.className = 'thingContent';

        newImgBox.appendChild(newPlusSign);
        newThingIcon.appendChild(newFileInput);
        newThingIcon.appendChild(newImgBox);
        newThingIcon.appendChild(newThingContent);
        container.appendChild(newThingIcon);
        const plusSigns = document.querySelectorAll('.plus-sign');

        let currentPlusSign = document.getElementById('plus-sign' + target.id.replace('file-input', ''));
        if (currentPlusSign) {
            currentPlusSign.classList.remove('hidden');
        }
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
</script>

</html>