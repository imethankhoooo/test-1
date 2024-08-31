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

// 创建数据库连接
$conn = new mysqli('localhost', 'root', '', 'php-assginment');

// 检查连接是否成功
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

    // 保存banner图片
     $bannerImgPath = null;

    // 检查文件是否正确上传
    if (isset($_FILES['eventImgInput']['tmp_name']) && !empty($_FILES['eventImgInput']['tmp_name'])) {
        $bannerImgPath = $uploadDir . basename($_FILES['eventImgInput']['name']);
        if (move_uploaded_file($_FILES['eventImgInput']['tmp_name'], $bannerImgPath)) {
            echo "Banner 图片上传成功，路径为: $bannerImgPath <br>";
        } else {
            die("上传 banner 图片失败，检查文件上传是否正确。");
        }
    } else {
        echo "未检测到文件上传。<br>";
    }

    // 使用预处理语句插入数据到event表
    $stmt = $conn->prepare("INSERT INTO event (event_name, description, location, event_date, start_time, end_time, fee, event_host, phone, note, banner_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $title, $content, $venue, $event_date, $event_startTime ,$event_endTime, $fee, $host, $host_phone, $precautions, $bannerImgPath);
    
    if ($stmt->execute()) {
        $event_id = $conn->insert_id; // 获取刚插入的事件ID

        // 保存"Things to Bring"数据
        if (!empty($_POST['thingContent'])) {
            foreach ($_POST['thingContent'] as $index => $thingContent) {
                // 只在有文本和上传文件的情况下进行插入
                if (!empty($thingContent) && !empty($_FILES['file-input']['tmp_name'][$index])) {
                    $thing_image = null;
        
                    if (!empty($_FILES['file-input']['tmp_name'][$index])) {
                        $thing_image = file_get_contents($_FILES['file-input']['tmp_name'][$index]);
                    }
        
                    // 使用预处理语句插入数据到item表
                    $stmtItem = $conn->prepare("INSERT INTO item (event_id, description, logo) VALUES (?, ?, ?)");
                    $stmtItem->bind_param("iss", $event_id, $thingContent, $thing_image);
                    $stmtItem->execute();
                }
            }
        }

        echo "新记录插入成功";
    } else {
        echo "错误: " . $stmt->error;
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

    <div class="Page adminPage1 "></div>
    <div class="Page adminPage2 hidden"></div>
    <div class="Page adminPage3 hidden">
        
    </div>
    <div class="Page adminPage4 hidden">
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
    <div class="Page adminPage5 hidden">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method='post' enctype="multipart/form-data">

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
                        <td><label class="eventInformation">Precautions : </label></td>
                        <td><textarea name="Precautions" cols="30" rows="3"></textarea></td>
                    </tr>
                </table>
                <span class="line"></span>
                <h3 class="thingToBring">Thing to bring</h3>
                <div class="think-Container">
                    <div class="thingIcon">
                        <input type="file" id="file-input0" class="file-input" accept="image/*" style="display:none;" name="file-input[]"/>
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
            newFileInput.name = 'file-input[]';
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

</html>