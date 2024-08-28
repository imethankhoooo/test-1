<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
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

// 假设你获取的事件ID是通过GET请求传递的
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 1;

// 从数据库中获取事件详情
$sql = "SELECT event_name, description, location, event_date, time, fee, event_host, note, banner_image FROM event WHERE event_id =  ?";
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
    $event_time = $event['time'];
    $event_fee = $event['fee'];
    $event_host = $event['event_host'];
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


<div class="eventDetails">
    <!-- 事件Banner图 -->
    <div class="eventImgContainer">
        <img src="<?php echo htmlspecialchars($banner_image); ?>" alt="Event Image" class="eventImg">
    </div>
    
    <!-- 事件信息 -->
     
    <div class="contentBlock">
    <h3 class="eventType">Special Event</h3> <!-- Event Type -->
        <h2 class="title eventInformation"><?php echo htmlspecialchars($event_name); ?></h2> <!-- 事件名称 -->
        <p class="content eventInformation"><?php echo nl2br(htmlspecialchars($event_description)); ?></p> <!-- 事件描述 -->
        <span class="line"></span>
        <table class="inputTable">
                    
                    <tr>
                        <td><p class="eventInformation"><strong>Date:</strong></p></td>
                        <td><p class="eventDate"><?php echo htmlspecialchars($event_date); ?></p></td>
                    </tr>
                    <tr>
                        <td><p class="eventInformation"><strong>Time:</strong></p></td>
                        <td><p class="eventTime"><?php echo htmlspecialchars($event_time); ?></p></td>
                    </tr>
                    <tr>
                        <td><p class="eventInformation"><strong>Fee:</strong></p></td>
                        <td><p class="eventFee">$<span class="eventFee"><?php echo htmlspecialchars($event_fee); ?></p></td>
                    </tr>
                    <tr>
                        <td> <p class="eventInformation"><strong>Host:</strong></p></td>
                        <td><p class="eventHost"><?php echo htmlspecialchars($event_host); ?></p></td>
                    </tr>
                    <tr>
                        <td><p class="eventInformation"><strong>Venue:</strong></p></td>
                        <td><p class="eventVenue"><?php echo nl2br(htmlspecialchars($event_location)); ?></p></td>
                    </tr>
                    <tr>
                        <td> <p class="eventInformation"><strong>Precautions:</strong></p></td>
                        <td><p class="eventPrecautions"><?php echo nl2br(htmlspecialchars($event_note)); ?></p></td>
                    </tr>
                </table>
    

    <!-- Things to Bring -->
    <span class="line"></span>

        <h3 class="thingsToBring">Things to Bring</h3>
        <div class="think-Container">
        <div class="thingIcon">
        <?php if (!empty($things_to_bring)) { ?>
            
                <?php foreach ($things_to_bring as $thing) { ?>
                        <div class="img-box" style="border:0px;">
                        <?php if (!empty($thing['logo'])) { ?>
                            <img class="addImg" src="data:image/jpeg;base64,<?php echo base64_encode($thing['logo']); ?>" alt="Item Image" class="itemImage">
                        <?php } ?>
                        </div>
                        <p class="thingContent"><?php echo htmlspecialchars($thing['description']); ?></p>
                        </div>
                <?php } ?>
            
        <?php } else { ?>
            <p>No items to bring.</p>
        <?php } ?>
        <div class="button-container">
                    <input class="button" type="submit" name="submit" value="Add">
                    <input class="button" type="reset" name="reset" value="Reset">
                </div>
        </div>
        </div>
</div>

    </body>
    </html>
